<?php

function writeToFile($fileName, $content) {
    $handle = fopen($fileName, 'a');
    fwrite($handle, $content);
    fclose($handle);
}

function makeDirectory($directory) {
    foreach ($directory as $folder) {
        if ($folder && !is_dir($folder)) {
            if (!mkdir($folder, 0775)) {
                die('Failed to create folder ' . $folder);
            }
        }
    }
}

if (!empty($_POST)) {
    $moduleName = strtolower($_POST['module']);
    $baseUrl = ROOT . DS . $moduleName . DS;
    $directory = array(
        "$baseUrl",
        "{$baseUrl}view");
    foreach ($_POST as $key => $value) {
        if ($key == "form" && $value == 1) {
            $directory[] = "{$baseUrl}form";
        }
        if ($key == "table" && $value == 1) {
            $directory[] = "{$baseUrl}table";
        }
        if ($key == "model" && $value == 1) {
            $directory[] = "{$baseUrl}model";
        }
        if ($key == "menu" && $value == 1) {
            $directory[] = "{$baseUrl}menu";
        }
        if ($key == "template" && $value == 1) {
            $directory[] = "{$baseUrl}template";
        }
    }
    makeDirectory($directory);
    renderAcl($moduleName);
}

function renderAcl($module) {
    $module = ucfirst($module);
    $aclFile = <<<ACL
<?php
    class {$module}_Acl extends Core_Acl{
        }
ACL;
    writeToFile(ROOT . DS . lcfirst($module) . DS . "Acl.php", $aclFile);
}
?>
<form method="post" id="module" name="module">
    <ul style="list-style-type: none;margin:0px 0px;padding:0px 0px;width:450px;">
        <li>
            <label style="width:150px;float:left;" for="module">Module Name :</label>
            <input name="module" type="text" value="newmodule" class="masterTooltip" required="required" title="no capitals">
        </li>
        <li>
            <input name="form" type="checkbox" value="1" checked="checked">
            <label style="width:150px;float:left;" for="form">Form Folder </label>
        </li>
        <li>
            <input name="table" type="checkbox" value="1" checked="checked">
            <label style="width:150px;float:left;" for="table">Table Folder </label>
        </li>
        <li>
            <input name="model" type="checkbox" value="1" checked="checked">
            <label style="width:150px;float:left;" for="model">Model Folder</label>
        </li>        
        <li>
            <input name="menu" type="checkbox" value="1" checked="checked">
            <label style="width:150px;float:left;" for="menu">Menu Folder</label>
        </li>
        <li>
            <input name="template" type="checkbox" checked="checked">
            <label style="width:150px;float:left;" for="template">Template Folder </label>
        </li>
        <li>
            <label style="width:150px;float:left;" for="submit">&nbsp;</label>
            <input name="submit" type="submit" value="Submit">
        </li>
    </ul>
</form>