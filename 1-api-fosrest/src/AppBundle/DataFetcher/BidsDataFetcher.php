<?php

namespace AppBundle\DataFetcher;

use AppBundle\Entity\Bid;
use AppBundle\Model\ResponseDTO\BidItemsResponseDTO;
use AppBundle\Model\BidsFilterDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Knp\Component\Pager\Paginator;

class BidsDataFetcher
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param EntityManager $entityManager
     * @param Paginator $paginator
     */
    public function __construct(EntityManager $entityManager, Paginator $paginator)
    {
        $this->em = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * @param BidsFilterDTO $bidsFilterDTO
     * @param string $locale
     * @return \AppBundle\Model\ResponseDTO\BidItemsResponseDTO
     */
    public function getItemsResponse(BidsFilterDTO $bidsFilterDTO, string $locale)
    {
        $queryBuilder = $this->createQueryBuilder($locale);

        $this->applyFilter($bidsFilterDTO, $queryBuilder);
        $this->applyOrder($bidsFilterDTO, $queryBuilder);

        $paginator = $this->paginator->paginate(
            $queryBuilder,
            $bidsFilterDTO->getPage(),
            $bidsFilterDTO->limit
        );

        return new BidItemsResponseDTO($paginator, $bidsFilterDTO);
    }

    /**
     * @param BidsFilterDTO $priceListsFilterDTO
     * @param QueryBuilder $queryBuilder
     */
    private function applyFilter(BidsFilterDTO $priceListsFilterDTO, QueryBuilder $queryBuilder)
    {
        if ($priceListsFilterDTO->bids) {
            $queryBuilder->andWhere('bid.id IN (:bids)')
                ->setParameter('bids', $priceListsFilterDTO->bids);

            return;
        }

        if ($priceListsFilterDTO->target === Bid::TARGET_DEMAND) {
            $queryBuilder->andWhere('bid.direction = :direction')
                ->setParameter('direction', Bid::TARGET_DEMAND);
        }

        if ($priceListsFilterDTO->target === Bid::TARGET_OFFER) {
            $queryBuilder->andWhere('bid.direction = :direction')
                ->setParameter('direction', Bid::TARGET_OFFER);
        }

        if (null !== $priceListsFilterDTO->dateFrom) {
            $queryBuilder->andWhere('bid.createdAt >= :dateFrom')
                ->setParameter('dateFrom', $priceListsFilterDTO->dateFrom);
        }

        if (null !== $priceListsFilterDTO->dateTo) {
            $queryBuilder->andWhere('bid.createdAt <= :dateTo')
                ->setParameter('dateTo', $priceListsFilterDTO->dateTo);
        }

        if (null !== $priceListsFilterDTO->country) {
            $queryBuilder->andWhere('userProfile.country = :country')
                ->setParameter('country', $priceListsFilterDTO->country);
        }

        if (null !== $priceListsFilterDTO->region) {
            $queryBuilder->andWhere('userProfile.region = :region')
                ->setParameter('region', $priceListsFilterDTO->region);
        }

        if ($priceListsFilterDTO->commodities) {
            $queryBuilder->andWhere('bid.commodity IN(:commodities)')
                ->setParameter('commodities', $priceListsFilterDTO->commodities);
        }

        if ($priceListsFilterDTO->name) {
            $queryBuilder->andWhere('LOWER(userProfile.company) LIKE :company')
                ->setParameter('company', '%'.mb_strtolower($priceListsFilterDTO->name).'%');
        }
    }

    /**
     * @param BidsFilterDTO $priceListsFilterDTO
     * @param QueryBuilder $queryBuilder
     */
    private function applyOrder(BidsFilterDTO $priceListsFilterDTO, QueryBuilder $queryBuilder)
    {
        switch ($priceListsFilterDTO->order) {
            case BidsFilterDTO::ORDER_BY_COMMODITY_NAME :
                $queryBuilder->orderBy('commodityTranslations.name', 'ASC');
                break;

            case BidsFilterDTO::ORDER_BY_COMPANY_NAME :
                $queryBuilder->orderBy('userProfile.company', 'ASC');
                break;

            case BidsFilterDTO::ORDER_BY_DATE :
            default:
                $queryBuilder->orderBy('bid.createdAt', 'DESC');
                break;
        }
    }

    /**
     * @param string $locale
     * @return QueryBuilder
     */
    private function createQueryBuilder(string $locale)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('NEW AppBundle\\Model\\BidDTO(
                bid.id, 
                bid.createdAt, 
                bid.direction, 
                commodityTranslations.name,
                bid.priceMax,
                userProfile.country,
                regionTranslations.name,
                userProfile.company,
                bid.conditions,
                LOWER(:locale)
            )')
            ->from('AppBundle:Bid', 'bid')
            ->leftJoin('bid.unit', 'unit')
            ->leftJoin('unit.translations', 'unitTranslations', Expr\Join::WITH, 'unitTranslations.locale = :locale')
            ->leftJoin('bid.user', 'user')
            ->leftJoin('AppBundle:UserProfile', 'userProfile', Expr\Join::WITH, 'userProfile.id = user.id')
            ->leftJoin('userProfile.region', 'region')
            ->leftJoin('region.translations', 'regionTranslations', Expr\Join::WITH, 'regionTranslations.locale = :locale')
            ->leftJoin('bid.commodity', 'commodity')
            ->leftJoin('commodity.translations', 'commodityTranslations', Expr\Join::WITH, 'commodityTranslations.locale = :locale')
            ->setParameter('locale', $locale)
        ;
    }
}
