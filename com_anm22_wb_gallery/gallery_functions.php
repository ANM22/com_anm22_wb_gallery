<?php

class anm22_wb_galleries {

    private $galleries;
    private $categories;

    function __construct() {
        $galleries = array();
        $categories = array();
    }

    function getGalleriesArray() {
        return $this->galleries;
    }

    function swapGalleries($indexOne, $indexTwo) {
        $tempGallery;
        $tempGallery = $this->galleries[$indexOne];
        $this->galleries[$indexOne] = $this->galleries[$indexTwo];
        $this->galleries[$indexTwo] = $tempGallery;
    }

    function addCategory($categoryName) {
        $existing = false;
        if (!empty($this->categories)) {
            foreach ($this->categories as $key => $category) {
                if ($category == $categoryName) {
                    $existing = true;
                    break;
                }
            }
        }
        if (!$existing) {
            $this->categories[] = $categoryName;
        } else {
            /* Handle the case in which a category with the same name already exists */
        }
    }

    function removeCategory($categoryName) {
        if (!empty($this->categories)) {
            foreach ($this->categories as $key => $name) {
                if ($name == $categoryName) {
                    unset($this->categories[$key]);
                    break;
                }
            }
        }
    }

    function addGallery($galleryToAdd) {
        $this->galleries[] = $galleryToAdd;
    }

    function removeGallery($galleryTs) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                unset($this->galleries[$k]);
                $this->galleries = array_values($this->galleries);
                break;
            }
        }
    }

    function editGalleryById($galleryTs, $newGalleryTitle, $newGalleryCategory, $newGalleryPublic, $newGalleryDescription = "") {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                $this->galleries[$k]->setTitle($newGalleryTitle);
                $this->galleries[$k]->setCategory($newGalleryCategory);
                $this->galleries[$k]->setPublicBool($newGalleryPublic);
                $this->galleries[$k]->setDescription($newGalleryDescription);
                break;
            }
        }
    }

    function editImageById($galleryId, $imageId, $imageNewName, $imageNewTitle, $imageNewDescription) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                for ($j = 0; $j < $this->galleries[$k]->getImagesCount(); $j++) {
                    $arrayToWorkOn = $this->galleries[$k]->getImagesArray();
                    if ($arrayToWorkOn[$j]->getCreationDate() == $imageId) {
                        $arrayToWorkOn[$j]->setName($imageNewName);
                        $arrayToWorkOn[$j]->setTitle($imageNewTitle);
                        $arrayToWorkOn[$j]->setDescription($imageNewDescription);
                    }
                }
            }
        }
    }

    function getGalleryById($galleryTs) {
        $k;
        $galleryToBeReturned;
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                $galleryToBeReturned = $this->galleries[$k];
                break;
            }
        }
        return $galleryToBeReturned;
    }

    function addImageToGallery($galleryId, $newImage) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                $this->galleries[$k]->addImage($newImage);
                break;
            }
        }
    }

    function removeImageFromGallery($galleryId, $imageId) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                $this->galleries[$k]->removeImage($imageId);
                break;
            }
        }
    }

    function moveImageToTheLeft($galleryId, $imageId) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                for ($j = 0; $j < $this->galleries[$k]->getImagesCount(); $j++) {
                    $arrayToWorkOn = $this->galleries[$k]->getImagesArray();
                    if ($arrayToWorkOn[$j]->getCreationDate() == $imageId && $j != 0) {
                        $temp = $arrayToWorkOn[$j - 1];
                        $this->galleries[$k]->setImageAtIndex($arrayToWorkOn[$j], ($j - 1));
                        $this->galleries[$k]->setImageAtIndex($temp, $j);
                        $this->galleries[$k]->setImagesWholeArray(array_values($this->galleries[$k]->getImagesArray()));
                        break;
                    }
                }
            }
        }
    }

    function moveImageToTheRight($galleryId, $imageId) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                for ($j = 0; $j < $this->galleries[$k]->getImagesCount(); $j++) {
                    $arrayToWorkOn = $this->galleries[$k]->getImagesArray();
                    if ($arrayToWorkOn[$j]->getCreationDate() == $imageId && $j != ($this->galleries[$k]->getImagesCount() - 1)) {
                        $temp = $arrayToWorkOn[$j + 1];
                        $this->galleries[$k]->setImageAtIndex($arrayToWorkOn[$j], ($j + 1));
                        $this->galleries[$k]->setImageAtIndex($temp, $j);
                        $this->galleries[$k]->setImagesWholeArray(array_values($this->galleries[$k]->getImagesArray()));
                        break;
                    }
                }
            }
        }
    }

    function editImageContainingGallery($imageId, $oldGalleryId, $newGalleryId) {
        $imageToSwitch;
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $oldGalleryId) {
                $imageToSwitch = $this->galleries[$k]->getImageById($imageId);
                $this->galleries[$k]->removeImage($imageId);
                break;
            }
        }
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $newGalleryId) {
                $this->galleries[$k]->addImage($imageToSwitch);
                break;
            }
        }
    }

    function toStringDebug() {
        return 'Sono presenti ' . $this->getGalleriesCount() . ' gallery.';
    }

    function getGalleriesCount() {
        return count($this->galleries);
    }

    function getCategories() {
        return $this->categories;
    }

    function toJsonReadyArray() {
        $associativeArray = array();
        for ($i = 0; $i < $this->getGalleriesCount(); $i++) {
            $galleryNew = array();
            $galleryNew["title"] = $this->galleries[$i]->getTitle();
            $galleryNew["category"] = $this->galleries[$i]->getCategory();
            $galleryNew["creationDate"] = $this->galleries[$i]->getCreationDate();
            $galleryNew["publicBool"] = $this->galleries[$i]->getPublicBool();
            $galleryNew["description"] = $this->galleries[$i]->getDescription();
            $galleryNew["images"] = array();
            for ($j = 0; $j < $this->galleries[$i]->getImagesCount(); $j++) {
                $imageNew = array();
                $arrayToWorkOn = $this->galleries[$i]->getImagesArray();
                $imageNew["name"] = $arrayToWorkOn[$j]->getName();
                $imageNew["extension"] = $arrayToWorkOn[$j]->getExtension();
                $imageNew["title"] = $arrayToWorkOn[$j]->getTitle();
                $imageNew["description"] = $arrayToWorkOn[$j]->getDescription();
                $imageNew["creationDate"] = $arrayToWorkOn[$j]->getCreationDate();
                $galleryNew["images"][] = $imageNew;
            }
            $associativeArray[] = $galleryNew;
        }
        return $associativeArray;
    }
    
    function importValuesFromJson($jsonArray) {
        if ($jsonArray) {
            foreach ($jsonArray as $gallery) {
                // Genero oggetto gallery e importo i valori
                $galleryObject = new anm22_wb_gallery();
                $galleryObject->importValuesFromJson($gallery);

                // Aggiungo la gallery alla collezione
                $this->addGallery($galleryObject);
                // Aggiungo la categoria alla collezione
                $this->addCategory($galleryObject->getCategory());
            }
        }
    }

}

/**
 * Classe dell'oggetto gallery
 */
class anm22_wb_gallery {

    protected $images;
    protected $title;
    protected $description;
    protected $creationDate;
    protected $category;
    protected $publicBool;

    function __construct($galleryTitle = "", $galleryCategory = "", $galleryPublic = "", $galleryCreationDate = "", $galleryDescription = "") {
        $this->images = array();
        $this->title = $galleryTitle;
        $this->creationDate = $galleryCreationDate;
        $this->category = $galleryCategory;
        $this->publicBool = $galleryPublic;
        $this->description = $galleryDescription;
    }

    function getImageById($imageId) {
        $imageToBeReturned = NULL;
        for ($k = 0; $k < $this->getImagesCount(); $k++) {
            if ($this->images[$k]->getCreationDate() == $imageId) {
                $imageToBeReturned = $this->images[$k];
                break;
            }
        }
        return $imageToBeReturned;
    }

    function getImagesArray() {
        return $this->images;
    }

    function setImagesWholeArray($imagesArray) {
        $this->images = $imagesArray;
    }

    function addImage($imageToAdd) {
        $this->images[] = $imageToAdd;
    }

    function removeImage($imageToRemoveId) {
        foreach ($this->images as $key => $image) {
            if ($imageToRemoveId == $image->getCreationDate()) {
                unset($this->images[$key]);
                $this->images = array_values($this->images);
                break;
            }
        }
    }

    /**
     * Ottieni titolo gallery
     * 
     * @return string
     */
    function getTitle() {
        return $this->title;
    }

    /**
     * Imposta titolo gallery
     * 
     * @param string $newTitle
     * @return $this
     */
    function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    /**
     * Ottieni descrizione gallery
     * 
     * @return string
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * Imposta descrizione gallery
     * 
     * @param string $description Descrizione
     * @return $this
     */
    function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function setCreationDate($newCreationDate) { /* For Debugging */
        $this->creationDate = $newCreationDate;
        return $this;
    }

    function getCategory() {
        return $this->category;
    }

    function setCategory($newCategory) {
        $this->category = $newCategory;
        return $this;
    }

    function getPublicBool() {
        return $this->publicBool;
    }

    function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    function getImagesByDate() {
        $imagesOrderedByDate = array();
        $tmpArray = array();
        $tmpArray = $this->images;
        $tmpElement;
        $elementCount = count($tmpArray);
        for ($i = 0; $i < $elementCount; $i++) {
            $tmpElement = $tmpArray[0];
            $tmpKey = 0;
            foreach ($tmpArray as $key => $element) {
                if ($tmpElement->getCreationDate() < $element->getCreationDate()) {
                    $tmpElement = $element;
                    $tmpKey = $key;
                }
            }
            $imagesOrderedByDate[] = $tmpElement;
            unset($tmpArray[$key]);
        }
        return $imagesOrderedByDate;
    }

    function addImageAtIndex($newImage, $index) {
        $firstPart = array_slice($this->images, 0, $index);
        $secondPart = array_slice($this->images, $index);
        $this->images = $firstPart;
        $this->images[] = $newImage;
        $this->images = array_merge($this->images, $secondPart);
    }

    function setImageAtIndex($image, $index) {
        $this->images[$index] = $image;
    }

    function getImagesCount() {
        return count($this->images);
    }

    function toStringDebug() {
        return 'Sono presenti ' . $this->getImagesCount() . ' immagini nella gallery ' . $this->getTitle() . '.';
    }
    
    /**
     * Importa proprietà oggetto dall'array associativo
     * 
     * @param mixed[] $gallery Array associativo dell'oggetto gallery
     * @return $this
     */
    function importValuesFromJson($gallery) {
        // Importo dati gallery
        $this->setTitle($gallery["title"]);
        $this->setCategory($gallery["category"]);
        $this->setPublicBool($gallery["publicBool"]);
        $this->setCreationDate($gallery["creationDate"]);
        if (isset($gallery['description'])) {
            $this->setDescription($gallery["description"]);
        } else {
            $this->setDescription("");
        }
        
        // Importo immagini
        foreach ($gallery["images"] as $image) {
            $imageObject = new anm22_wb_img($image["name"], $image["extension"], $image["title"], $image["creationDate"], $image["description"]);
            $this->addImage($imageObject);
        }
        
        return $this;
    }

}

/**
 * Classe oggetto immagine
 */
class anm22_wb_img {

    protected $name;
    protected $extension;
    protected $title;
    protected $creationDate;
    protected $publicBool;
    protected $description;

    function __construct($imageName = "", $imageExtension = "", $imageTitle = "", $imageCreationTime = "", $imageDescription = "") {
        $this->name = $imageName;
        $this->extension = $imageExtension;
        $this->title = $imageTitle;
        $this->creationDate = $imageCreationTime;
        $this->description = $imageDescription;
    }

    function getName() {
        return $this->name;
    }

    function setName($newName) {
        $this->name = $newName;
        return $this;
    }

    function getExtension() {
        return $this->extension;
    }

    function setExtension($newExtension) { /* Debugging only */
        $this->extension = $newExtension;
        return $this;
    }

    function getTitle() {
        return $this->title;
    }

    function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($newDescription) {
        $this->description = $newDescription;
        return $this;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function setCreationDate($newCreationDate) { /* For Debugging */
        $this->creationDate = $newCreationDate;
        return $this;
    }

    function getPublicBool() {
        return $this->publicBool;
    }

    function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    function getPermalink() {
        return $this->getCreationDate() . '-' . str_replace(' ', '-', $this->getName());
    }

}