<?php
class VideoDetailsFormProvider {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function createUploadForm() {

        $fileinput = $this->createFileInput();
        $descriptionInput = $this->createDescriptionInput(null);
        $titleInput = $this->createTitleInput(null);
        $categoryInput = $this->createCategoriesInput(null);
        $privacyInput = $this->createPrivacyInput(null);
        $buttonInput = $this->createUploadButton(null);
        
        return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                $fileinput  
                $titleInput
                $descriptionInput
                $categoryInput
                $privacyInput
                $buttonInput
        </form>";


    }

    public function createEditDetailsForm($video) {
        $descriptionInput = $this->createDescriptionInput($video->getDescription($video));
        $titleInput = $this->createTitleInput($video->getTitle($video));
        $categoryInput = $this->createCategoriesInput($video->getCategory($video));
        $privacyInput = $this->createPrivacyInput($video->getPrivacy($video));
        $saveButton = $this->createSaveButton();
        
        return "<form method='POST'>
                $titleInput
                $descriptionInput
                $categoryInput
                $privacyInput
                $saveButton
        </form>";


    }

    private function createFileInput() {

        return "<div class='form-group'>
                    <label for='exampleFormControlFile1'>Your file</label>
                    <input type='file' class='form-control-file' id='exampleFormControlFile1' name='fileInput' required>
                </div>";
    }

    private function createTitleInput($value) {
        if ($value == null) $value = "";
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Title' name='titleInput' value='$value'>
                </div>";
    }

    private function createDescriptionInput($value) {
        if ($value == null) $value = "";

        return "<div class='form-group'>
                    <textarea class='form-control' placeholder='Description' name='descriptionInput' rows='3'>$value</textarea>
                </div>";
    }

    private function createPrivacyInput($value) {
        if ($value == null) $value = "";

        $privateSelected    = ($value ==0 ) ? "selected='selected'" : "";
        $publicSelected     = ($value ==0 ) ? "selected='selected'" : "";
        return "<div class='form-group'>
                    <select class='form-control' name='privacyInput'>
                        <option value='0' $privateSelected >Private</option>
                        <option value='1' $publicSelected>Public</option>
                    </select>
                </div>";
    }

    private function createCategoriesInput($value) {
        if ($value == null) $value = "";

        $data = DataAccess::getDataArray($this->con,"SELECT * FROM categories",null,"");
        
        $html = "<div class='form-group'>
                    <select class='form-control' name='categoryInput'>";


        foreach ($data as $row) {

            $id = $row["id"];
            $name = $row["name"];
            $selected     = ($id == $value ) ? "selected='selected'" : "";

            $html .= "<option $selected value='$id'>$name</option>";
        }
               
        $html .= "</select>
                </div>";

        return $html;

    }

    private function createUploadButton() {
        return "<button type='submit' class='btn btn-primary' name='uploadButton'>Upload</button>";
    }

    private function createSaveButton() {
        return "<button type='submit' class='btn btn-primary' name='saveButton'>Save</button>";
    }
}

?>