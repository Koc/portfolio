<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bid;
use AppBundle\Form\BidsFilterForm;
use AppBundle\Form\BidType;
use AppBundle\Model\ResponseDTO\BidItemResponseDTO;
use AppBundle\Model\BidsFilterDTO;
use AppBundle\Model\ResponseDTO\StatusResponse;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class BidsController extends FOSRestController
{
    /**
     * @Rest\View()
     * @ApiDoc(
     *  description="Get list bids",
     *  input="AppBundle\Form\BidsFilterForm",
     *  output="AppBundle\Model\ResponseDTO\BidItemsResponseDTO",
     *  section="Bids"
     * )
     */
    public function getBidsAction(Request $request)
    {
        $priceListsFilterDTO = new BidsFilterDTO();

        $form = $this->createForm(BidsFilterForm::class, $priceListsFilterDTO);
        $form->submit($request->query->all(), false);
        if (!$form->isValid()) {
            return $form->getErrors();
        }

        if (!$this->isGranted('ROLE_USER')) {
            $priceListsFilterDTO->bids = $this->getDoctrine()
                ->getRepository('AppBundle:Bid')
                ->findBy([], ['createdAt' => 'DESC'], 0, 50);
        }

        return $this->get('app.bids_data_fetcher')->getItemsResponse($priceListsFilterDTO, $request->getLocale());
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *   description="Create new bid",
     *   input="AppBundle\Form\BidType",
     *   output="AppBundle\Model\ResponseDTO\BidItemResponseDTO",
     *   section="Bids"
     * )
     * @Security("has_role('ROLE_USER')")
     */
    public function postBidsAction(Request $request)
    {
        $bid = new Bid();
        $form = $this->createForm(BidType::class, $bid);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form->getErrors();
        }

        $bid->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($bid);
        $em->flush();

        $bidsFilterDTO = new BidsFilterDTO();
        $bidsFilterDTO->bids = [$bid];
        $bids = $this->get('app.bids_data_fetcher')->getItemsResponse($bidsFilterDTO, $request->getLocale());

        return new BidItemResponseDTO(reset($bids->items));
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *   description="Edit bid",
     *   input="AppBundle\Form\BidType",
     *   output="AppBundle\Model\ResponseDTO\BidItemResponseDTO",
     *   section="Bids"
     * )
     * @ParamConverter("bid", class="AppBundle:Bid")
     * @Security("has_role('ROLE_USER') and (bid.getUser().getId() == user.getId())")
     */
    public function patchBidsAction(Request $request, Bid $bid)
    {
        $form = $this->createForm(BidType::class, $bid);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form->getErrors();
        }

        $this->getDoctrine()->getManager()->flush();

        $bidsFilterDTO = new BidsFilterDTO();
        $bidsFilterDTO->bids = [$bid];
        $bids = $this->get('app.bids_data_fetcher')->getItemsResponse($bidsFilterDTO, $request->getLocale());

        return new BidItemResponseDTO(reset($bids->items));
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *   description="Delete bid",
     *   output="AppBundle\Model\ResponseDTO\StatusResponse",
     *   section="Bids"
     * )
     * @ParamConverter("bid", class="AppBundle:Bid")
     * @Security("has_role('ROLE_USER') and (bid.getUser().getId() == user.getId())")
     */
    public function deleteBidsAction(Bid $bid)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bid);
        $em->flush();

        return new StatusResponse('Bid removed.');
    }
}
