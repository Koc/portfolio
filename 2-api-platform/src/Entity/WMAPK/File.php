<?php

namespace App\Entity\WMAPK;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File AS HttpFoundationFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddableFile;

/**
 * @ORM\Table(name="files", schema="wmapk")
 * @ApiResource(attributes={
 *        "force_eager"=false,
 *        "normalization_context"={"groups"={"wmapk_file"}}
 *     },
 *     itemOperations={
 *          "get" = {"method"="GET"},
 *          "delete" = {"method"="DELETE"},
 *          "api_upload_file"={
 *             "method"="POST",
 *             "route_name"="api_file_upload",
 *             "swagger_context" = {
 *                 "summary" = "Upload file.",
 *                 "parameters" = {
 *                     {
 *                         "name" = "file",
 *                         "required" = true,
 *                         "type" = "file",
 *                         "in" = "formData"
 *                     }
 *                 },
 *                 "consumes"={"multipart/form-data"},
 *                 "responses" = {
 *                     200 = {
 *                         "schema" = {"type" = "object", "properties" = {
 *                               "id" = {"type" = "integer"},
 *                               "updatedAt" = {"type" = "string"},
 *                               "createdAt" = {"type" = "string"},
 *                               "name" = {"type" = "string"},
 *                               "mimeType" = {"type" = "string"},
 *                               "size" = {"type" = "integer"}
 *                      }}
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     *
     * @Groups({"wmapk_file"})
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="wmapk_files", fileNameProperty="file.name", size="file.size", mimeType="file.mimeType", originalName="file.originalName", dimensions="file.dimensions")
     *
     * @var HttpFoundationFile
     */
    private $uploadedFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File", columnPrefix=false)
     *
     * @var EmbeddableFile
     */
    private $file;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"wmapk_file"})
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @Groups({"wmapk_file"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->file = new EmbeddableFile();
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * @return HttpFoundationFile|UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(HttpFoundationFile $uploadedFile = null)
    {
        $this->uploadedFile = $uploadedFile;
        if ($this->uploadedFile instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return EmbeddableFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param EmbeddableFile $file
     */
    public function setFile(EmbeddableFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @Groups({"wmapk_file"})
     */
    public function getName(): string
    {
        return $this->getFile()->getName();
    }

    /**
     * @Groups({"wmapk_file"})
     */
    public function getSize(): string
    {
        return $this->getFile()->getSize();
    }

    /**
     * @Groups({"wmapk_file"})
     */
    public function getMimeType(): string
    {
        return $this->getFile()->getMimeType();
    }
}