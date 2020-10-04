<?php

/**
 * Galleries collection object
 *
 * @version 3.3
 * 
 */
class anm22_wb_galleries {

    private $galleries;
    private $categories;

    function __construct() {
        $galleries = array();
        $categories = array();
    }

    /**
     * All galleries
     * 
     * @return anm22_wb_gallery[]
     */
    public function getGalleriesArray() {
        return $this->galleries;
    }

    /**
     * Swap galleries position
     * 
     * @param anm22_wb_gallery $indexOne
     * @param anm22_wb_gallery $indexTwo
     */
    public function swapGalleries($indexOne, $indexTwo) {
        $tempGallery;
        $tempGallery = $this->galleries[$indexOne];
        $this->galleries[$indexOne] = $this->galleries[$indexTwo];
        $this->galleries[$indexTwo] = $tempGallery;
    }

    /**
     * Add gallery category
     * 
     * @param string $categoryName Category name
     */
    public function addCategory($categoryName) {
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

    /**
     * @param string $categoryName Category name
     */
    public function removeCategory($categoryName) {
        if (!empty($this->categories)) {
            foreach ($this->categories as $key => $name) {
                if ($name == $categoryName) {
                    unset($this->categories[$key]);
                    break;
                }
            }
        }
    }

    /**
     * Add gallery
     * 
     * @param anm22_wb_gallery $galleryToAdd Gallery to add
     * @return $this
     */
    public function addGallery($galleryToAdd) {
        $this->galleries[] = $galleryToAdd;
        return $this;
    }

    /**
     * Remove gallery
     * 
     * @param integer $galleryTs Gallery id
     * @return $this
     */
    public function removeGallery($galleryTs) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                unset($this->galleries[$k]);
                $this->galleries = array_values($this->galleries);
                break;
            }
        }
        return $this;
    }

    /**
     * Edit gallery by id
     * 
     * @param integer $galleryTs Gallery id
     * @param string $newGalleryTitle Gallery title
     * @param string $newGalleryCategory Gallery category name
     * @param boolean $newGalleryPublic Gallery visibility status
     * @param string $newGalleryDescription Gallery description
     * @return $this
     */
    public function editGalleryById($galleryTs, $newGalleryTitle, $newGalleryCategory, $newGalleryPublic, $newGalleryDescription = "") {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                $this->galleries[$k]->setTitle($newGalleryTitle);
                $this->galleries[$k]->setCategory($newGalleryCategory);
                $this->galleries[$k]->setPublicBool($newGalleryPublic);
                $this->galleries[$k]->setDescription($newGalleryDescription);
                break;
            }
        }
        return $this;
    }

    /**
     * Edit image
     * 
     * @param integer $galleryId Gallery id
     * @param integer $imageId Image id
     * @param string $imageNewName Image name
     * @param string $imageNewTitle Image title
     * @param string $imageNewDescription Image description
     * @return $this
     */
    public function editImageById($galleryId, $imageId, $imageNewName, $imageNewTitle, $imageNewDescription) {
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
        return $this;
    }

    /**
     * Get gallery by id
     * 
     * @param integer $galleryTs Gallery Id
     * @return anm22_wb_gallery
     */
    public function getGalleryById($galleryTs) {
        $galleryToBeReturned = null;
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryTs) {
                $galleryToBeReturned = $this->galleries[$k];
                break;
            }
        }
        return $galleryToBeReturned;
    }

    /**
     * Get gallery by title
     * 
     * @param integer $galleryTitle Gallery title
     * @return anm22_wb_gallery
     */
    public function getGalleryByTitle($galleryTitle) {
        $galleryToBeReturned = null;
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getTitle() == $galleryTitle) {
                $galleryToBeReturned = $this->galleries[$k];
                break;
            }
        }
        return $galleryToBeReturned;
    }

    /**
     * Add image to gallery
     * 
     * @param integer $galleryId Gallery id
     * @param anm22_wb_img $newImage Image to add
     * @return $this
     */
    public function addImageToGallery($galleryId, $newImage) {
        return $this->addItemToGallery($galleryId, $newImage);
    }

    /**
     * Add item to gallery
     * 
     * @param integer $galleryId Gallery id
     * @param anm22_wb_item_gallery $newItem Item to add
     * @return $this
     */
    public function addItemToGallery($galleryId, $newItem) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                $this->galleries[$k]->addItem($newItem);
                break;
            }
        }
        return $this;
    }

    /**
     * Remove image to gallery
     * 
     * @param integer $galleryId Gallery id
     * @param anm22_wb_img $imageId Gallery to remove
     * @return $this
     */
    public function removeImageFromGallery($galleryId, $imageId) {
        for ($k = 0; $k < $this->getGalleriesCount(); $k++) {
            if ($this->galleries[$k]->getCreationDate() == $galleryId) {
                $this->galleries[$k]->removeImage($imageId);
                break;
            }
        }
        return $this;
    }

    /**
     * Change image position
     * 
     * @param integer $galleryId Gallery id
     * @param anm22_wb_img $imageId Image to move
     * @return $this
     */
    public function moveImageToTheLeft($galleryId, $imageId) {
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
        return $this;
    }

    /**
     * Change image position
     * 
     * @param integer $galleryId Gallery id
     * @param anm22_wb_img $imageId Image to move
     * @return $this
     */
    public function moveImageToTheRight($galleryId, $imageId) {
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
        return $this;
    }

    /**
     * Move image to gallery
     * 
     * @param integer $imageId Image id
     * @param integer $oldGalleryId Move image from
     * @param integer $newGalleryId Move image to
     * @return $this
     */
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
        return $this;
    }

    public function toStringDebug() {
        return 'Sono presenti ' . $this->getGalleriesCount() . ' gallery.';
    }

    /**
     * Get number of galleries
     * 
     * @return int
     */
    public function getGalleriesCount() {
        return count($this->galleries);
    }

    /**
     * Get categories list
     * 
     * @return string[]
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Gallery serialization as associative array
     * 
     * @return type
     */
    public function toJsonReadyArray() {
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
                $imageNew["type"] = $arrayToWorkOn[$j]->getType();
                $imageNew["name"] = $arrayToWorkOn[$j]->getName();
                $imageNew["extension"] = $arrayToWorkOn[$j]->getExtension();
                $imageNew["title"] = $arrayToWorkOn[$j]->getTitle();
                $imageNew["description"] = $arrayToWorkOn[$j]->getDescription();
                $imageNew["creationDate"] = $arrayToWorkOn[$j]->getCreationDate();
                if ($arrayToWorkOn[$j]->getType() == 'video') {
                    $imageNew["videoId"] = $arrayToWorkOn[$j]->getVideoId();
                }
                $galleryNew["images"][] = $imageNew;
            }
            $associativeArray[] = $galleryNew;
        }
        return $associativeArray;
    }
    
    /**
     * Import data from json
     * 
     * @param type $jsonArray
     * @return $this
     */
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
        return $this;
    }

}

/**
 * Gallery object
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

    /**
     * Get image by id
     * 
     * @param integer $imageId Image id
     * @return anm22_wb_img
     */
    public function getImageById($imageId) {
        return $this->getItemById($imageId);
    }

    /**
     * Get item by id
     * 
     * @param integer $itemId Gallery item id
     * @return anm22_wb_gallery_item
     */
    public function getItemById($itemId) {
        $imageToBeReturned = NULL;
        for ($k = 0; $k < $this->getImagesCount(); $k++) {
            if ($this->images[$k]->getCreationDate() == $itemId) {
                $imageToBeReturned = $this->images[$k];
                break;
            }
        }
        return $imageToBeReturned;
    }

    /**
     * Get images
     * 
     * @return anm22_wb_img[]
     */
    public function getImagesArray() {
        return $this->images;
    }

    /**
     * Add images to array
     * 
     * @param anm22_wb_img[] $imagesArray Images to add
     * @return $this
     */
    public function setImagesWholeArray($imagesArray) {
        $this->images = $imagesArray;
        return $this;
    }

    /**
     * Add image to gallery
     * 
     * @param anm22_wb_img $imageToAdd Image to add
     * @return $this
     */
    public function addImage($imageToAdd) {
        return $this->addItem($imageToAdd);
    }

    /**
     * Add item to gallery
     * 
     * @param anm22_wb_gallery_item $itemToAdd Image to add
     * @return $this
     */
    public function addItem($itemToAdd) {
        $this->images[] = $itemToAdd;
        return $this;
    }

    /**
     * Remove image
     * 
     * @param integer $imageToRemoveId
     * @return $this
     */
    public function removeImage($imageToRemoveId) {
        foreach ($this->images as $key => $image) {
            if ($imageToRemoveId == $image->getCreationDate()) {
                unset($this->images[$key]);
                $this->images = array_values($this->images);
                break;
            }
        }
        return $this;
    }

    /**
     * Get gallery title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set gallery title
     * 
     * @param string $newTitle
     * @return $this
     */
    public function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    /**
     * Get gallery description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set gallery description
     * 
     * @param string $description Description
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($newCreationDate) { /* For Debugging */
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($newCategory) {
        $this->category = $newCategory;
        return $this;
    }

    public function getPublicBool() {
        return $this->publicBool;
    }

    public function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    /**
     * Get image ordered by date
     * 
     * @return anm22_wb_img[]
     */
    public function getImagesByDate() {
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

    /**
     * Add image
     * 
     * @param anm22_wb_img $newImage Image
     * @param integer $index Position
     * @return $this
     */
    public function addImageAtIndex($newImage, $index) {
        $firstPart = array_slice($this->images, 0, $index);
        $secondPart = array_slice($this->images, $index);
        $this->images = $firstPart;
        $this->images[] = $newImage;
        $this->images = array_merge($this->images, $secondPart);
        return $this;
    }

    /**
     * Set image
     * 
     * @param anm22_wb_img $image
     * @param integer $index
     * @return $this
     */
    public function setImageAtIndex($image, $index) {
        $this->images[$index] = $image;
        return $this;
    }

    /**
     * Get number of images
     * 
     * @return integer
     */
    public function getImagesCount() {
        return count($this->images);
    }

    public function toStringDebug() {
        return 'Sono presenti ' . $this->getImagesCount() . ' immagini nella gallery ' . $this->getTitle() . '.';
    }
    
    /**
     * Import data by gallery associative array
     * 
     * @param mixed[] $gallery Gallery associative array
     * @return $this
     */
    public function importValuesFromJson($gallery) {
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
        
        // Import items
        foreach ($gallery["images"] as $image) {
            if (!isset($image['type']) || !$image['type'] || ($image['type'] == 'img')) {
                $imageObject = new anm22_wb_img($image["name"], $image["extension"], $image["title"], $image["creationDate"], $image["description"]);
            } else {
                $imageObject = new anm22_wb_video($image["name"], $image["extension"], $image["title"], $image["videoId"], $image["creationDate"], $image["description"]);
            }
            $this->addImage($imageObject);
        }
        
        return $this;
    }

}

/**
 * Gallery item object
 */
class anm22_wb_gallery_item {

    protected $name;
    protected $extension;
    protected $title;
    protected $creationDate;
    protected $publicBool;
    protected $description;
    protected $type = 'img';

    function __construct($imageName = "", $imageExtension = "", $imageTitle = "", $imageCreationTime = "", $imageDescription = "") {
        $this->name = $imageName;
        $this->extension = $imageExtension;
        $this->title = $imageTitle;
        $this->creationDate = $imageCreationTime;
        $this->description = $imageDescription;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($newName) {
        $this->name = $newName;
        return $this;
    }

    public function getExtension() {
        return $this->extension;
    }

    /**
     * Set image extension. Debugging only.
     * 
     * @param type $newExtension
     * @return $this
     */
    public function setExtension($newExtension) {
        $this->extension = $newExtension;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($newCreationDate) { /* For Debugging */
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getPublicBool() {
        return $this->publicBool;
    }

    public function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    public function getPermalink() {
        return $this->getCreationDate() . '-' . str_replace(' ', '-', $this->getName());
    }

}

/**
 * Image object
 */
class anm22_wb_img extends anm22_wb_gallery_item {

    protected $name;
    protected $extension;
    protected $title;
    protected $creationDate;
    protected $publicBool;
    protected $description;
    protected $type = 'img';

    function __construct($imageName = "", $imageExtension = "", $imageTitle = "", $imageCreationTime = "", $imageDescription = "") {
        $this->name = $imageName;
        $this->extension = $imageExtension;
        $this->title = $imageTitle;
        $this->creationDate = $imageCreationTime;
        $this->description = $imageDescription;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($newName) {
        $this->name = $newName;
        return $this;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($newExtension) { /* Debugging only */
        $this->extension = $newExtension;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($newCreationDate) { /* For Debugging */
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getPublicBool() {
        return $this->publicBool;
    }

    public function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    public function getPermalink() {
        return $this->getCreationDate() . '-' . str_replace(' ', '-', $this->getName());
    }

}

/**
 * Video object
 */
class anm22_wb_video extends anm22_wb_gallery_item {

    protected $name;
    protected $extension;
    protected $title;
    protected $creationDate;
    protected $publicBool;
    protected $description;
    protected $type = 'video';
    
    protected $videoId;
    
    function __construct($imageName = "", $imageExtension = "", $imageTitle = "", $videoId = "", $imageCreationTime = "", $imageDescription = "") {
        $this->name = $imageName;
        $this->extension = $imageExtension;
        $this->title = $imageTitle;
        $this->creationDate = $imageCreationTime;
        $this->description = $imageDescription;
        $this->videoId = $videoId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($newName) {
        $this->name = $newName;
        return $this;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($newExtension) { /* Debugging only */
        $this->extension = $newExtension;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($newTitle) {
        $this->title = $newTitle;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * Method for debugging
     * 
     * @param int $newCreationDate Image id
     * @return $this
     */
    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
        return $this;
    }

    /**
     * Id del video
     * 
     * @return string
     */
    public function getVideoId() {
        return $this->videoId;
    }

    public function setVideoId($videoId) {
        $this->videoId = $videoId;
        return $this;
    }

    public function getPublicBool() {
        return $this->publicBool;
    }

    public function setPublicBool($newPublicBool) {
        $this->publicBool = $newPublicBool;
        return $this;
    }

    public function getPermalink() {
        return $this->getCreationDate() . '-' . str_replace(' ', '-', $this->getName());
    }

}