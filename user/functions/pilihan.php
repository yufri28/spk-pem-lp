<?php 
$kriteria = ["Jarak ke Lok", "Biaya Sewa", "Akses", "Tema"];
if(isset($_POST['prioritas_1'])){
    echo "<option value=''>-- Pilih prioritas 2 --</option>";
    $selectedOptions = $_POST['prioritas_1']; 
    $filteredOptions = array_diff($kriteria, $selectedOptions);
    foreach ($filteredOptions as $option) {
        echo "<option value='$option'>$option</option>";
    }
}

if(isset($_POST['prioritas_2'])){
    echo "<option value=''>-- Pilih prioritas 3 --</option>";
    $selectedOptions = $_POST['prioritas_2']; 
    $filteredOptions = array_diff($kriteria, $selectedOptions);
    foreach ($filteredOptions as $option) {
        echo "<option value='$option'>$option</option>";
    }
}
if(isset($_POST['prioritas_3'])){
    echo "<option value=''>-- Pilih prioritas 4 -</option>";
    $selectedOptions = $_POST['prioritas_3']; 
    $filteredOptions = array_diff($kriteria, $selectedOptions);
    foreach ($filteredOptions as $option) {
        echo "<option value='$option'>$option</option>";
    }
}
if(isset($_POST['prioritas_4'])){
    echo "<option value=''>-- Pilih prioritas 5 --</option>";
    $selectedOptions = $_POST['prioritas_4']; 
    $filteredOptions = array_diff($kriteria, $selectedOptions);
    foreach ($filteredOptions as $option) {
        echo "<option value='$option'>$option</option>";
    }
}

?>