<?php

/**
 * Gallery plugin for ANM22 WebBase CMS.
 * 
 * @author Andrea Menghi <andrea.menghi@anm22.it>
 */
class com_anm22_wb_editor_page_element_gallery extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_gallery";
    var $elementPlugin = "com_anm22_wb_gallery";

    var $elementClassName = "Gallery";
    var $elementClassIcon = "images/Icone/gallery.png";

    var $cssClass;

    var $galleryElementTitle;                   /*Titolo del blocchetto delle gallery*/
    var $mode;					/*Modalità di visualizzazione, che può essere all(tutte), category(singola categoria), single (singola gallery)*/
    var $selectedCategory;			/*Categoria selezionata*/
    var $selectedGalleryTitle;                  /*Titolo selezionato*/
    var $elementFunction;			/*Funzionalità del blocchetto, che può essere Preview, Preivew Only, Show Only e Multi Show*/
    var $imgView;				/*Permette di scegliere la modalità di visualizzazione, mp -> agnificPopup, blank -> nuova scheda, same -> stessa scheda*/
    var $galleryNumber;				/*Numero di gallery visibili*/
    var $galleryCols;				/*Colonne di gallery*/
    var $externalLink;				/*Link esterno di apertura della gallery in modalità show*/
    var $galleryTitleShow;			/*Scelta sul mostrare o no il titolo della gallery*/
    var $thumbnailGallery;			/*Scelta sul mostrare o no il thumbnail della gallery*/
    var $galleryCreationDate;                   /*Scelta sul mostrare o no la data di creazione della gallery*/
    protected $showGalleryDescription = false;
    var $imgNumber;				/*Numero di immagini di una determinata gallery mostrate*/
    var $imgCols;				/*Numero di colonne di suddivisione delle immagini (DEPRECATED)*/
    var $imgTitleShow;				/*Scelta sul mostrare o no il titolo di un'immagine*/
    var $imgCreationDate;			/*Scelta sul mostrare o no la data di creazione di un'immagine*/
    var $imgDesc;				/*Scelta sul mostrare o no la descrizione di un'immagine*/
    protected $seoTags = false;

    var $galleryId = 0;				/*Id della gallery*/
    var $seeButton;				/*Scelta sul mostrare o no il button visualizza album*/

    /**
     * @deprecated since editor 3.0
     * 
     * Method to init the element.
     * 
     * @param SimpleXMLElement $xml Element data
     * @return void
     */
    public function importXMLdoJob($xml)
    {
        $this->galleryElementTitle = htmlspecialchars_decode($xml->galleryElementTitle);
        $this->mode = htmlspecialchars_decode($xml->mode);
        $this->selectedCategory = htmlspecialchars_decode($xml->selectedCategory);
        $this->selectedGalleryTitle = htmlspecialchars_decode($xml->selectedGalleryTitle);
        $this->elementFunction = htmlspecialchars_decode($xml->elementFunction);
        $this->imgView = htmlspecialchars_decode($xml->imgView);
        $this->galleryNumber = htmlspecialchars_decode($xml->galleryNumber);
        $this->galleryCols = htmlspecialchars_decode($xml->galleryCols);
        $this->externalLink = htmlspecialchars_decode($xml->externalLink);
        $this->galleryTitleShow = htmlspecialchars_decode($xml->galleryTitleShow);
        $this->thumbnailGallery = htmlspecialchars_decode($xml->thumbnailGallery);
        $this->galleryCreationDate = htmlspecialchars_decode($xml->galleryCreationDate);
        if (isset($xml->showGalleryDescription)) {
            $this->showGalleryDescription = htmlspecialchars_decode($xml->showGalleryDescription);
        }
        $this->imgNumber = htmlspecialchars_decode($xml->imgNumber);
        $this->imgCols = htmlspecialchars_decode($xml->imgCols);
        $this->imgTitleShow = htmlspecialchars_decode($xml->imgTitleShow);
        $this->imgCreationDate = htmlspecialchars_decode($xml->imgCreationDate);
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
        $this->seeButton = htmlspecialchars_decode($xml->seeButton);
        $this->imgDesc = htmlspecialchars_decode($xml->imgDesc);
        if (isset($xml->seoTags)) {
            $this->seoTags = $xml->seoTags;
        }

        $this->initLogics();
    }

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        if ($data['galleryElementTitle'] ?? false) {
            $this->galleryElementTitle = htmlspecialchars_decode($data['galleryElementTitle']);
        }
        $this->mode = htmlspecialchars_decode($data['mode']);
        $this->selectedCategory = htmlspecialchars_decode($data['selectedCategory']);
        $this->selectedGalleryTitle = htmlspecialchars_decode($data['selectedGalleryTitle']);
        $this->elementFunction = htmlspecialchars_decode($data['elementFunction']);
        $this->imgView = htmlspecialchars_decode($data['imgView']);
        $this->galleryNumber = htmlspecialchars_decode($data['galleryNumber']);
        $this->galleryCols = htmlspecialchars_decode($data['galleryCols']);
        if (isset($data['externalLink']) && $data['externalLink']) {
            $this->externalLink = htmlspecialchars_decode($data['externalLink']);
        }
        $this->galleryTitleShow = htmlspecialchars_decode($data['galleryTitleShow']);
        $this->thumbnailGallery = htmlspecialchars_decode($data['thumbnailGallery']);
        $this->galleryCreationDate = htmlspecialchars_decode($data['galleryCreationDate']);
        if ($data['showGalleryDescription'] ?? false) {
            $this->showGalleryDescription = htmlspecialchars_decode($data['showGalleryDescription']);
        }
        $this->imgNumber = htmlspecialchars_decode($data['imgNumber']);
        if (isset($data['imgCols']) && $data['imgCols']) {
            $this->imgCols = htmlspecialchars_decode($data['imgCols']);
        }
        $this->imgTitleShow = htmlspecialchars_decode($data['imgTitleShow']);
        $this->imgCreationDate = htmlspecialchars_decode($data['imgCreationDate']);
        if (isset($data['cssClass']) && $data['cssClass']) {
            $this->cssClass = htmlspecialchars_decode($data['cssClass']);
        }
        $this->seeButton = htmlspecialchars_decode($data['seeButton']);
        $this->imgDesc = htmlspecialchars_decode($data['imgDesc']);
        if (isset($data['seoTags'])) {
            $this->seoTags = $data['seoTags'];
        }

        $this->initLogics();
    }

    /**
     * Method With the logics to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    protected function initLogics()
    {
        include_once __DIR__ . '/gallery_functions.php';

        /*Reading the JSON file*/
        $jsonFile = NULL;
        $jsonFile = file_get_contents($this->page->getHomeFolderRelativePHPURL() . "gallery/gallery.json");

        /*Decoding the file to an associative array*/
        $jsonArray = json_decode($jsonFile,true);
        $galleriesContainer = new anm22_wb_galleries();

        /*Elaborating array into objects*/
        if (is_array($jsonArray)) {
            /*Creates the galleries container*/
            foreach ($jsonArray as $gallery) {
                /*Objects*/
                $galleryObject = new anm22_wb_gallery($gallery["title"], $gallery["category"], $gallery["publicBool"], $gallery["creationDate"], $gallery["description"] ?? null);
                foreach ($gallery["images"] as $image) {
                    $imageObject = new anm22_wb_img($image["name"], $image["extension"], $image["title"], $image["creationDate"], $image["description"]);
                    $galleryObject->addImage($imageObject);
                }
                $galleriesContainer->addGallery($galleryObject);
            }
        }
        
        /* Seo tags */
        if ($this->seoTags) {
            $isSingleGallery = false;
            if ($this->elementFunction == "show") {
                $isSingleGallery = true;
                $gallery = $galleriesContainer->getGalleryByTitle($this->selectedGalleryTitle);
            }
            if ($this->elementFunction == "preview") {
                $galleryId = intval($this->getGalleryIdFromPermalink($this->page->getPageSublink()));
                if (isset($galleryId) && ($galleryId != 0)) {
                    $gallery = $galleriesContainer->getGalleryById($galleryId);
                    $isSingleGallery = true;
                }
            }
            
            if ($isSingleGallery) {
                if (($gallery->getTitle()) && ($gallery->getTitle() != "")) {
                    $this->page->title = $gallery->getTitle();
                }
                if (($gallery->getDescription()) && ($gallery->getDescription() != "")) {
                    $descriptionString = str_replace("\n", " ", str_replace('"', "", $gallery->getDescription()));
                    
                    if (strlen($descriptionString) > 160) {
                        $this->page->description = htmlspecialchars(substr($descriptionString, 0, 160)) . "...";
                    } else {
                        $this->page->description = htmlspecialchars($descriptionString);
                    }
                }
                if ($gallery->getImagesArray()) {
                    $imageId = $gallery->getImagesArray()[0]->getCreationDate();
                    $this->page->image = "https://" . $_SERVER['HTTP_HOST'] . "/img/" . $gallery->getImagesArray()[0]->getPermalink() . "/img.png";
                }
            }
        }
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    public function show()
    {
        
        include_once __DIR__ . '/gallery_functions.php';

        /*Reading the JSON file*/

        $jsonFile = NULL;
        $jsonFile = file_get_contents($this->page->getHomeFolderRelativePHPURL() . "gallery/gallery.json");

        /*Decoding the file to an associative array*/

        $jsonArray = json_decode($jsonFile,true);
        $galleriesContainer = new anm22_wb_galleries();
        $galleriesContainer->importValuesFromJson($jsonArray);

        echo '<div class="com_anm22_wb_editor_page_element_gallery ' . $this->cssClass . '">';
            if ($this->galleryElementTitle != '') {
                // Titolo blocchetto
                echo '<h1 class="gallery_element_title">' . $this->galleryElementTitle . '</h1>';
            }
            if (isset($_GET['galleryId'])) {
                $galleryId = htmlentities($_GET['galleryId']);
            } else {
                $galleryId = null;
            }
            switch ($this->elementFunction) {			/*Switch sulla base della funzione del blocchetto gallery (preview/show, show-only, preview-only, multi-show*/
                case 'previewOnly':				/*Switch per la function preview-only*/
                    switch ($this->mode) {		/*Switch sulla base delle gallery mostrate (all, category, single) per la funzione di preview-only*/
                        case 'all':				/*Caso all function preview-only*/
                            $n = 0;
                            for ($i = ($galleriesContainer->getGalleriesCount() - 1); $i >= 0; $i--) {
                                $gArray = $galleriesContainer->getGalleriesArray();
                                $galleryToWorkOn = $gArray[$i];
                                if ($galleryToWorkOn->getPublicBool()) {
                                    echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                        echo '<div class="gallery_container_preview" style="float:left;width:'.(100/$this->galleryCols).'%;">';
                                            if ($this->galleryTitleShow) {
                                                echo '<h2 class="gallery_preview_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                            }
                                            if ($this->showGalleryDescription) {
                                                echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                            }
                                            if ($this->thumbnailGallery) {
                                                if($galleryToWorkOn->getImagesCount() == 0){
                                                    echo '<div src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                } else {
                                                    $iArray = $galleryToWorkOn->getImagesArray();
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate() . '.png" class="img_container" />';
                                                }
                                            }
                                            if ($this->galleryCreationDate) {
                                                echo '<p class="gallery_creation_date">';
                                                    echo '<span class="date-label">';
                                                        if ($this->page->getPageLanguage() == 'it') {
                                                            echo 'Data';
                                                        } else {
                                                            echo 'Date';
                                                        }
                                                    echo '</span>';
                                                    echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                echo '</p>';
                                            }
                                            if ($this->seeButton) {
                                                switch ($this->imgView) {
                                                    case 'mp':
                                                        ?>
                                                        <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                                        <script>
                                                        $(document).ready(function() {
                                                            $('.see-album-button-<?=$galleryToWorkOn->getCreationDate()?>').click(function(e){
                                                                e.preventDefault();
                                                                $('.<?=$galleryToWorkOn->getCreationDate();?>').magnificPopup({type:'image', gallery:{enabled:true}});
                                                                $('.<?=$galleryToWorkOn->getCreationDate();?>').each(function(index, element) {
                                                                    if(index==0){
                                                                        $(this).click();
                                                                    }
                                                                });
                                                            });
                                                        });
                                                        </script>
                                                        <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                                        <?
                                                        $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                                        for($a=0;$a<$galleryToWorkOn->getImagesCount();$a++){
                                                            ?>
                                                            <a class="<?=$galleryToWorkOn->getCreationDate();?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$a]->getPermalink()?>/" rel="group"></a>
                                                            <?
                                                        }
                                                        ?>
                                                        <button class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage()){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        <?
                                                        break;
                                                    case 'same':
                                                        ?>
                                                        <script>
                                                            $(document).ready(function(e) {
                                                                $('.<?=$galleryToWorkOn->getCreationDate()?>-form button').click(function(e) {
                                                                    e.preventDefault();
                                                                    $('.<?=$galleryToWorkOn->getCreationDate()?>-form').submit();
                                                                });
                                                            });
                                                        </script>
                                                        <form class="<?=$galleryToWorkOn->getCreationDate()?>-form" action="<?=$this->externalLink?><?=$this->getPermalinkWithId($galleryToWorkOn->getCreationDate(),$galleryToWorkOn->getTitle())?>/">
                                                            <button type="submit" class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage() == "it"){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        </form>
                                                        <?
                                                        break;
                                                }
                                            }
                                        echo '</div>';
                                    echo '</a>';
                                    $n++;
                                    if($n==$this->galleryNumber){
                                        break;
                                    }
                                }
                            }
                            echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                            break;
                        case 'category':		/* Caso category function preview-only */
                            $n = 0;
                            for ($i=($galleriesContainer->getGalleriesCount()-1);$i>=0;$i--) {
                                $gArray = $galleriesContainer->getGalleriesArray();
                                if ($gArray[$i]->getCategory() == $this->selectedCategory) {
                                    $galleryToWorkOn = $gArray[$i];
                                } else {
                                    continue;
                                }
                                if ($galleryToWorkOn->getPublicBool()) {
                                    echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                        echo '<div class="gallery_container_preview" style="float:left;width:' . (100/$this->galleryCols) . '%;">';
                                            if ($this->galleryTitleShow) {
                                                echo '<h2 class="gallery_preview_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                            }
                                            if ($this->showGalleryDescription) {
                                                echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                            }
                                            if ($this->thumbnailGallery) {
                                                if($galleryToWorkOn->getImagesCount() == 0){
                                                    echo '<img src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                } else {
                                                    $iArray = $galleryToWorkOn->getImagesArray();
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate() . '.png" class="img_container" />';
                                                }
                                            }
                                                            
                                            if ($this->galleryCreationDate) {
                                                echo '<p class="gallery_creation_date">';
                                                    echo '<span class="date-label">';
                                                        if ($this->page->getPageLanguage() == 'it') {
                                                            echo 'Data';
                                                        } else {
                                                            echo 'Date';
                                                        }
                                                    echo '</span>';
                                                    echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                echo '</p>';
                                            }
                                            
                                            if ($this->seeButton) {
                                                switch($this->imgView){
                                                    case 'mp':
                                                        ?>
                                                        <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $('.see-album-button-<?=$galleryToWorkOn->getCreationDate()?>').click(function(e){
                                                                    e.preventDefault();
                                                                    $('.<?=$galleryToWorkOn->getCreationDate();?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                                                    $('.<?=$galleryToWorkOn->getCreationDate();?>').each(function(index, element) {
                                                                        if(index==0){
                                                                            $(this).click();
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                        <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                                        <?
                                                        $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                                        for($a=0;$a<$galleryToWorkOn->getImagesCount();$a++){
                                                            ?>
                                                            <a class="<?=$galleryToWorkOn->getCreationDate();?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$a]->getPermalink()?>/" rel="group"></a>
                                                            <?
                                                        }
                                                        ?>
                                                        <button class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage()){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        <?
                                                        break;
                                                    case 'same':
                                                        ?>
                                                        <script>
                                                            $(document).ready(function(e) {
                                                                $('.<?=$galleryToWorkOn->getCreationDate()?>-form button').click(function(e) {
                                                                    e.preventDefault();
																	$('.<?=$galleryToWorkOn->getCreationDate()?>-form').submit();
                                                                });
                                                            });
                                                        </script>
                                                        <form class="<?=$galleryToWorkOn->getCreationDate()?>-form" action="<?=$this->externalLink?><?=$this->getPermalinkWithId($galleryToWorkOn->getCreationDate(),$galleryToWorkOn->getTitle())?>/">
                                                            <button type="submit" class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage()){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        </form>
                                                        <?
                                                        break;
                                                }
                                            }
                                        echo '</div>';
                                    echo '</a>';
                                    $n++;
                                    if($n==$this->galleryNumber){
                                        break;
                                    }
                                }
                            }
                            echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                            break;
                        case 'single':		/* Caso single function preview-only */
                            $n = 0;
                            for ($i=($galleriesContainer->getGalleriesCount()-1);$i>=0;$i--) {
                                $gArray = $galleriesContainer->getGalleriesArray();
                                if ($gArray[$i]->getTitle() == $this->selectedGalleryTitle) {
                                    $galleryToWorkOn = $gArray[$i];
                                } else {
                                    continue;
                                }
                                if ($galleryToWorkOn->getPublicBool()) {
                                    echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                        echo '<div class="gallery_container_preview" style="float:left;width:' . (100/$this->galleryCols) . '%;">';
                                            if ($this->galleryTitleShow) {
                                                echo '<h1 class="gallery_preview_title">' . $galleryToWorkOn->getTitle() . '</h1>';
                                            }
                                            if ($this->showGalleryDescription) {
                                                echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                            }
                                            if($this->thumbnailGallery){
                                                if($galleryToWorkOn->getImagesCount() == 0){
                                                    echo '<img src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                } else {
                                                    $iArray = $galleryToWorkOn->getImagesArray();
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate().'.png" class="img_container" />';
                                                }
                                            }
                                            if ($this->galleryCreationDate) {
                                                echo '<p class="gallery_creation_date">';
                                                    echo '<span class="date-label">';
                                                        if ($this->page->getPageLanguage() == 'it') {
                                                            echo 'Data';
                                                        } else {
                                                            echo 'Date';
                                                        }
                                                    echo '</span>';
                                                    echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                echo '</p>';
                                            }
                                            if ($this->seeButton) {
                                                switch ($this->imgView) {
                                                    case 'mp':
                                                        ?>
                                                        <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                                        <script>
                                                        $(document).ready(function() {
                                                            $('.see-album-button-<?=$galleryToWorkOn->getCreationDate()?>').click(function(e){
                                                                e.preventDefault();
                                                                $('.<?=$galleryToWorkOn->getCreationDate();?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                                                $('.<?=$galleryToWorkOn->getCreationDate();?>').click();
                                                            });
                                                        });
                                                        </script>
                                                        <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                                        <?
                                                        $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                                        for($a=0;$a<$galleryToWorkOn->getImagesCount();$a++){
                                                            ?><a class="<?=$galleryToWorkOn->getCreationDate();?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$a]->getPermalink()?>/" rel="group"></a><?
                                                        }
                                                        ?>
                                                        <button class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage()){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        <?
                                                        break;
                                                    case 'same':
                                                        ?>
                                                        <script>
                                                            $(document).ready(function(e) {
                                                                $('.<?=$galleryToWorkOn->getCreationDate()?>-form button').click(function(e) {
                                                                    e.preventDefault();
                                                                    $('.<?=$galleryToWorkOn->getCreationDate()?>-form').submit();
                                                                });
                                                            });
                                                        </script>
                                                        <form class="<?=$galleryToWorkOn->getCreationDate()?>-form" action="<?=$this->externalLink?><?=$this->getPermalinkWithId($galleryToWorkOn->getCreationDate(),$galleryToWorkOn->getTitle())?>/">
                                                            <button type="submit" class="see-album-button-<?=$galleryToWorkOn->getCreationDate()?>" id="<?=$galleryToWorkOn->getCreationDate()?>"><? if($this->page->getPageLanguage()){ ?><?=$galleryToWorkOn->getTitle()?><? } else { ?><?=$galleryToWorkOn->getTitle()?><? } ?></button>
                                                        </form>
                                                        <?
                                                        break;
                                                }
                                            }
                                        echo '</div>';
                                    echo '</a>';
                                    $n++;
                                    if($n==$this->galleryNumber){
                                        break;
                                    }
                                }
                            }
                            echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                            break;
                    }
                    break;				/* Break della function preview-only */
                case 'show':		/* Case show-only di elementFunction */
                    echo '<div class="gallery_container">';
                        $galleryToWorkOn = $galleriesContainer->getGalleryByTitle($this->selectedGalleryTitle);
                        if ($this->galleryTitleShow) {
                            echo '<h1 class="gallery_single_title">' . $this->selectedGalleryTitle . '</h1>';
                        }
                        if ($this->showGalleryDescription) {
                            echo '<div class="gallery_view_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                        }
                        echo '<div class="imgs-container">';
                            for ($i=0;$i<$galleryToWorkOn->getImagesCount() && $i<$this->imgNumber;$i++) {
                                $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                echo '<div class="img_external_container">';
                                    if ($this->imgTitleShow) {
                                        echo '<h2 class="img_title">' . $imagesArrayToWorkOn[$i]->getTitle() . '</h2>';
                                    }
                                    switch ($this->imgView) {	/* Switch per l'imgView function show-only */
                                        case 'mp':			/*Case Magnific Popup function show-only*/
                                            ?>
                                            <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                            <script>
                                                $(document).ready(function() {
                                                    $('.<?=$galleryToWorkOn->getCreationDate()?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                                });
                                            </script>
                                            <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                            <a class="<?=$galleryToWorkOn->getCreationDate()?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                            </a>
                                            <?
                                            break;
                                        case 'blank':		/*Case blank (altra scheda) function show-only*/
                                            ?>
                                            <a target="_blank" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" class="<?=$galleryToWorkOn->getCreationDate?>" rel="group">
                                                <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                            </a>
                                            <?
                                            break;
                                        case 'same':		/*Case same (stessa scheda) function show-only*/
                                            ?>
                                            <a target="_parent" class="<?=$galleryToWorkOn->getCreationDate?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                            </a>
                                            <?
                                            break;
                                        case 'showAll':		/* Case show all - function show-only */
                                            echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '.png" class="img_container" />';
                                            break;
                                    }
                                    if ($this->imgDesc)  {
                                        echo '<p class="img_description">' . $imagesArrayToWorkOn[$i]->getDescription() . '</p>';
                                    }
                                    if ($this->imgCreationDate) {
                                        echo '<p class="img_creation_date">';
                                            echo '<span class="date-label">';
                                                if ($this->page->getPageLanguage() == 'it') {
                                                    echo 'Data';
                                                } else {
                                                    echo 'Date';
                                                }
                                            echo '</span>';
                                            echo date('d-m-Y',$imagesArrayToWorkOn[$i]->getCreationDate());
                                        echo '</p>';
                                    }
                                    echo '<div class="clear-both-div" style="clear:both;"></div>';
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                    break;						/*Break dell'elementFunction show-only*/
                case 'multiShow':			/* Case per l'elementFunction multi-show */
                    switch($this->mode) {
                        case 'all':			/* Case all function multi-show */
                            $galleryArray = $galleriesContainer->getGalleriesArray();
                            for ($l=0;$l<$galleriesContainer->getGalleriesCount() && $l<$this->galleryNumber;$l++) {
                                echo '<div class="gallery_container">';
                                    $galleryToWorkOn = $galleryArray[$l];
                                    if ($this->galleryTitleShow) {
                                        echo '<h2 class="gallery_single_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                    }
                                    if ($this->showGalleryDescription) {
                                        echo '<div class="gallery_view_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                    }
                                    for ($i=0;$i<$galleryToWorkOn->getImagesCount();$i++) {
                                        $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                        echo '<div class="img_external_container" style="float:left;">';
                                            if ($this->imgTitleShow) {
                                                echo '<h3 class="img_title">' . $imagesArrayToWorkOn[$i]->getTitle() . '</h3>';
                                            }
                                            switch($this->imgView){
                                                case 'mp':		/*Case mp mode all function multi-show*/
                                                    ?>
                                                    <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('.<?=$galleryToWorkOn->getCreationDate()?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                                        });
                                                    </script>
                                                    <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                                    <a class="<?=$galleryToWorkOn->getCreationDate()?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                        <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                    </a>
                                                    <?
                                                break;                  /*Break mp mode all function multi-show*/
                                                case 'blank':		/*Case blank mode all function multi-show*/
                                                    ?>
                                                    <a target="_blank" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" class="<?=$galleryToWorkOn->getCreationDate?>" rel="group">
                                                        <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                    </a>
                                                    <?
                                                break;                  /*Break blank mode all function multi-show*/
                                                case 'same':		/*Case same mode all function multi-show*/
                                                    ?>
                                                    <a target="_parent" class="<?=$galleryToWorkOn->getCreationDate?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                        <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                    </a>
                                                    <?
                                                    break;		/*Break same mode all function multi-show*/
                                                case 'showAll':		/* Case show all - mode all - function multi-show */
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '.png" class="img_container" />';
                                                    break;
                                            }
                                            if ($this->imgDesc){
                                                echo '<p class="img_description">' . $imagesArrayToWorkOn[$i]->getDescription() . '</p>';
                                            }
                                            if ($this->imgCreationDate){
                                                echo '<p class="img_creation_date">';
                                                    echo '<span class="date-label">';
                                                        if ($this->page->getPageLanguage() == 'it') {
                                                            echo 'Data';
                                                        } else {
                                                            echo 'Date';
                                                        }
                                                    echo '</span>';
                                                    echo date('d-m-Y',$imagesArrayToWorkOn[$i]->getCreationDate());
                                                echo '</p>';
                                            }
                                            echo '<div class="clear-both-div" style="clear:both;"></div>';
                                        echo '</div>';
                                    }
                                    echo '<div class="clear-both-div" style="clear: both;"></div>';
                                echo '</div>';
                            }
                            break;		/*Break all function multi-show*/
                        case 'category':	/*Case category function multi-show*/
                            $galleryArray = $galleriesContainer->getGalleriesArray();
                            $lReached = 0;
                            for ($l=0;$l<$galleriesContainer->getGalleriesCount() && $lReached<$this->galleryNumber;$l++) {
                                $galleryToWorkOn = $galleryArray[$l];
                                if($galleryToWorkOn->getCategory()==$this->selectedCategory){
                                    echo '<div class="gallery_container">';
                                        if ($this->galleryTitleShow) {
                                            echo '<h2 class="gallery_single_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                        }
                                        if ($this->showGalleryDescription) {
                                            echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                        }
                                        for ($i=0;$i<$galleryToWorkOn->getImagesCount();$i++) {
                                            $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                            ?>
                                            <div class="img_external_container" style="float:left;">
                                                <?
                                                if ($this->imgTitleShow){
                                                    echo '<h3 class="img_title">' . $imagesArrayToWorkOn[$i]->getTitle() . '</h3>';
                                                }
                                                switch($this->imgView){
                                                    case 'mp':		/*Case mp mode category function multi-show*/
                                                        ?>
                                                        <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                                        <script>
                                                            $(document).ready(function() {
                                                                    $('.<?=$galleryToWorkOn->getCreationDate()?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                                            });
                                                        </script>
                                                        <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                                        <a class="<?=$galleryToWorkOn->getCreationDate()?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                            <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                        </a>
                                                        <?
                                                        break;			/*Break mp mode category function multi-show*/
                                                    case 'blank':               /*Case blank mode category function multi-show*/
                                                        ?>
                                                        <a target="_blank" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" class="<?=$galleryToWorkOn->getCreationDate?>" rel="group">
                                                            <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                        </a>
                                                        <?
                                                        break;			/*Break blank mode category function multi-show*/
                                                    case 'same':                /*Case same mode category function multi-show*/
                                                        ?>
                                                        <a target="_parent" class="<?=$galleryToWorkOn->getCreationDate?>" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>img/<?=$imagesArrayToWorkOn[$i]->getPermalink()?>/" rel="group">
                                                            <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>gallery/<?=$imagesArrayToWorkOn[$i]->getCreationDate()?>_thumb.png" class="img_container" />
                                                        </a>
                                                        <?
                                                        break;			/*Break same mode category function multi-show*/
                                                    case 'showAll':		/* Case show all - mode category - function multi-show */
                                                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '.png" class="img_container" />';
                                                        break;
                                                }
                                                if($this->imgDesc){
                                                    echo '<p class="img_description">' . $imagesArrayToWorkOn[$i]->getDescription() . '</p>';
                                                }
                                                if($this->imgCreationDate){
                                                    echo '<p class="img_creation_date">';
                                                        echo '<span class="date-label">';
                                                            if ($this->page->getPageLanguage() == 'it') {
                                                                echo 'Data';
                                                            } else {
                                                                echo 'Date';
                                                            }
                                                        echo '</span>';
                                                        echo date('d-m-Y',$imagesArrayToWorkOn[$i]->getCreationDate());
                                                    echo '</p>';
                                                }
                                                echo '<div class="clear-both-div" style="clear:both;"></div>';
                                            echo '</div>';
                                        }
                                        echo '<div class="clear-both-div" style="clear:both;"></div>';
                                    echo '</div>';
                                    $lReached++;
                                }
                            }
                            break;	/* Break category function multi-show */
                    }
                    break;		/* Break dell'elementFunction multi-show */
                case 'preview':		/* Case dell'elementFunction preview */
                    $galleryId = intval($this->getGalleryIdFromPermalink($this->page->getPageSublink()));
                    if (isset($galleryId) && ($galleryId != 0)) {			/*Function show del preview/show attivata, passato come argomento della GET l'id gallery*/
                        echo '<div class="gallery_container">';
                            $galleryToWorkOn = $galleriesContainer->getGalleryById($galleryId);
                            if ($this->galleryTitleShow) {
                                echo '<h1 class="gallery_single_title">' . $galleriesContainer->getGalleryById($galleryId)->getTitle() . '</h1>';
                            }
                            if ($this->showGalleryDescription) {
                                echo '<div class="gallery_view_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                            }
                            if ($this->imgView=='mp') {
                                ?>
                                <script src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/js/jquery.magnific-popup.js"></script>
                                <script>
                                    $(document).ready(function() {
                                        $('.<?=$galleryToWorkOn->getCreationDate()?>').magnificPopup({type:'image',gallery:{enabled:true}});
                                    });
                                </script>
                                <link rel="stylesheet" href="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_gallery/css/magnific-popup.css" />
                                <?
                            }
                            echo '<div class="imgs-container">';
                                for ($i=0;$i<$galleryToWorkOn->getImagesCount();$i++) {
                                    $imagesArrayToWorkOn = $galleryToWorkOn->getImagesArray();
                                    echo '<div class="img_external_container">';
                                        if ($this->imgTitleShow) {
                                            echo '<h2 class="img_title">' . $imagesArrayToWorkOn[$i]->getTitle() . '</h2>';
                                        }
                                        switch($this->imgView) {
                                            case 'mp':                  /* Case mp mode "single" function "show" del preview/show */
                                                echo '<a class="' . $galleryToWorkOn->getCreationDate() . '" href="' . $this->page->getHomeFolderRelativeHTMLURL() . 'img/' . $imagesArrayToWorkOn[$i]->getPermalink() . '/" rel="group">';
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '_thumb.png" class="img_container" />';
                                                echo '</a>';
                                                break;			/* Break mp mode "single" function "show" del preview/show */
                                            case 'blank':               /* Case blank mode "single" function "show" del preview/show */
                                                echo '<a target="_blank" href="' . $this->page->getHomeFolderRelativeHTMLURL() . 'img/' . $imagesArrayToWorkOn[$i]->getPermalink() . '/" class="' . $galleryToWorkOn->getCreationDate . '" rel="group">';
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '_thumb.png" class="img_container" />';
                                                echo '</a>';
                                                break;			/* Break blank mode "single" function "show" del preview/show */
                                            case 'same':                /* Case same mode "single" function "show" del preview/show */
                                                echo '<a target="_parent" class="' . $galleryToWorkOn->getCreationDate . '" href="' . $this->page->getHomeFolderRelativeHTMLURL() . 'img/' . $imagesArrayToWorkOn[$i]->getPermalink() . '/" rel="group">';
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '_thumb.png" class="img_container" />';
                                                echo '</a>';
                                                break;			/* Break same mode "single" function "show" del preview/show */
                                            case 'showAll':		/* Case show all - function preview */
                                                if ($imagesArrayToWorkOn[$i]->getType() == 'video') {
                                                    echo '<iframe src="https://www.youtube.com/embed/' . $imagesArrayToWorkOn[$i]->getVideoId() . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="video-container"></iframe>';
                                                } else {
                                                    echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $imagesArrayToWorkOn[$i]->getCreationDate() . '.png" class="img_container" />';
                                                }
                                                break;
                                        }
                                        if ($this->imgCreationDate) {
                                            echo '<p class="img_creation_date">';
                                                echo '<span class="date-label">';
                                                    if ($this->page->getPageLanguage() == 'it') {
                                                        echo 'Data';
                                                    } else {
                                                        echo 'Date';
                                                    }
                                                echo '</span>';
                                                echo date('d-m-Y',$imagesArrayToWorkOn[$i]->getCreationDate());
                                            echo '</p>';
                                        }
                                        if($this->imgDesc){
                                            echo '<p class="img_description">' . $imagesArrayToWorkOn[$i]->getDescription() . '</p>';
                                        }
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    } else {		/* Function preview del preview/show attivata */
                        switch ($this->mode) {
                            case 'all':		/* Case all function "preview" del preview/show */
                                $n = 0;
                                for ($i=($galleriesContainer->getGalleriesCount()-1);$i>=0;$i--) {
                                    $gArray = $galleriesContainer->getGalleriesArray();
                                    $galleryToWorkOn = $gArray[$i];
                                    if ($galleryToWorkOn->getPublicBool()) {
                                        echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                            echo '<div class="gallery_container_preview" style="float:left;width:' . (100/$this->galleryCols) . '%;">';
                                                if ($this->galleryTitleShow) {
                                                    echo '<h2 class="gallery_preview_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                                }
                                                if ($this->showGalleryDescription) {
                                                    echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                                }
                                                if ($this->thumbnailGallery) {
                                                    if ($galleryToWorkOn->getImagesCount() == 0) {
                                                        echo '<img src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                    } else {
                                                        $iArray = $galleryToWorkOn->getImagesArray();
                                                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate() . '.png" class="img_container" />';
                                                    }
                                                }
                                                if ($this->galleryCreationDate){
                                                    echo '<p class="gallery_creation_date">';
                                                        echo '<span class="date-label">';
                                                            if ($this->page->getPageLanguage() == 'it') {
                                                                echo 'Data';
                                                            } else {
                                                                echo 'Date';
                                                            }
                                                        echo '</span>';
                                                        echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                    echo '</p>';
                                                }
                                            echo '</div>';
                                        echo '</a>';
                                        $n++;
                                        if($n==$this->galleryNumber){
                                            break;
                                        }
                                    }
                                }
                                echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                                break;
                            case 'category':	/* Case category function "preview" del preview/show */
                                $n = 0;
                                for($i=($galleriesContainer->getGalleriesCount()-1);$i>=0;$i--){
                                    $gArray = $galleriesContainer->getGalleriesArray();
                                    if($gArray[$i]->getCategory() == $this->selectedCategory){
                                        $galleryToWorkOn = $gArray[$i];
                                    } else {
                                        continue;
                                    }
                                    if ($galleryToWorkOn->getPublicBool()) {
                                        echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                            echo '<div class="gallery_container_preview" style="float:left;width:' . (100/$this->galleryCols) . '%;">';
                                                if ($this->galleryTitleShow) {
                                                    echo '<h2 class="gallery_preview_title">' . $galleryToWorkOn->getTitle() . '</h2>';
                                                }
                                                if ($this->showGalleryDescription) {
                                                    echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                                }
                                                if ($this->thumbnailGallery){
                                                    if ($galleryToWorkOn->getImagesCount() == 0) {
                                                        echo '<img src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                    } else {
                                                        $iArray = $galleryToWorkOn->getImagesArray();
                                                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate() . '.png" class="img_container" />';
                                                    }
                                                }
                                                if ($this->galleryCreationDate){
                                                    echo '<p class="gallery_creation_date">';
                                                        echo '<span class="date-label">';
                                                            if ($this->page->getPageLanguage() == 'it') {
                                                                echo 'Data';
                                                            } else {
                                                                echo 'Date';
                                                            }
                                                        echo '</span>';
                                                        echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                    echo '</p>';
                                                }
                                            echo '</div>';
                                        echo '</a>';
                                        
                                        $n++;
                                        if ($n==$this->galleryNumber) {
                                            break;
                                        }
                                    }
                                }
                                echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                                break;
                            case 'single':	/* Case single function "preview" del preview/show */
                                $n = 0;
                                for ($i=($galleriesContainer->getGalleriesCount()-1);$i>=0;$i--) {
                                    $gArray = $galleriesContainer->getGalleriesArray();
                                    if ($gArray[$i]->getTitle() == $this->selectedGalleryTitle) {
                                        $galleryToWorkOn = $gArray[$i];
                                    } else {
                                        continue;
                                    }
                                    if ($galleryToWorkOn->getPublicBool()) {
                                        echo '<a href="' . $this->getGalleryShowPage() . '?galleryId=' . $galleryToWorkOn->getCreationDate() . '">';
                                            echo '<div class="gallery_container_preview" style="float:left;width:' . (100/$this->galleryCols) . '%;">';
                                                if ($this->galleryTitleShow) {
                                                    echo '<h1 class="gallery_preview_title">' . decodeURI(escape($galleryToWorkOn->getTitle())) . '</h1>';
                                                }
                                                if ($this->showGalleryDescription) {
                                                    echo '<div class="gallery_preview_description">' . nl2br($galleryToWorkOn->getDescription()) . '</div>';
                                                }
                                                if ($this->thumbnailGallery) {
                                                    if($galleryToWorkOn->getImagesCount() == 0){
                                                        echo '<img src="https://www.anm22.it/app/webbase/images/Icone/gallery.png" class="img_container" />';
                                                    } else {
                                                        $iArray = $galleryToWorkOn->getImagesArray();
                                                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'gallery/' . $iArray[0]->getCreationDate() . '.png" class="img_container" />';
                                                    }
                                                }
                                                if ($this->galleryCreationDate) {
                                                    echo '<p class="gallery_creation_date">';
                                                        echo '<span class="date-label">';
                                                            if ($this->page->getPageLanguage() == 'it') {
                                                                echo 'Data';
                                                            } else {
                                                                echo 'Date';
                                                            }
                                                        echo '</span>';
                                                        echo date('d-m-Y',$galleryToWorkOn->getCreationDate());
                                                    echo '</p>';
                                                }
                                            echo '</div>';
                                        echo '</a>';

                                        $n++;
                                        if($n==$this->galleryNumber){
                                            break;
                                        }
                                    }
                                }
                                echo '<div class="gallery_no_float_div" style="clear:both;"></div>';
                                break;
                        }
                    }
            }
        echo '<div style="clear:both;"></div>';
        echo '</div>';
    }

    private function getPermalinkWithId($id,$title)
    {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

        return $id . "-" . $clean;
    }

    private function getGalleryIdFromPermalink($perma)
    {
        // Prima controllo se è stata richiesta una gallery esplicitamente
        $galleryId = null;
        if (isset($_GET['galleryId'])) {
            $galleryId = intval($_GET['galleryId']);
        } else {
            $split = explode("-", $perma);
            $galleryId = intval($split[0]);
        }
        return $galleryId;
    }
    
    protected function getGalleryShowPage()
    {
        if ($this->externalLink) {
            return $this->externalLink;
        } else {
            return '';
        }
    }

}
