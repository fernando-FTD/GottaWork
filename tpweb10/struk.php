<?php
session_start();
if(!isset($_SESSION['hasil'])){
    header("Location: kasir.php");
    exit;
}
function hitungDiskon($totalBelanja){
    if($totalBelanja >= 100000){
        $persentaseDiskon=10;
    }elseif($totalBelanja >= 50000){
        $persentaseDiskon=5;
    }else{
        $persentaseDiskon=0;
    }
    $diskon*(int)($totalBelanja*($persentaseDiskon/100));
    $totalBayar*(int)($totalBelanja-$diskon);
    return array($persentaseDiskon,$diskon,$totalBayar);
}
$hasil=$_SESSION['hasil'];
$totalBelanja=$hasil['totalBelanja'];
$persentaseDiskon=$hasil['persentaseDiskon'];
$diskon=$hasil['diskon'];
$totalBayar=$hasil['totalBayar'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Diskon</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="hasil">
    <h3>Struk Hasil Belanja</h3>
    <p><span class="label">Total Belanja</span> <span class="nilai">Rp <?php echo number_format($totalBelanja,0,',','.'); ?></span></p>
    <p><span class="label">Diskon(%)</span> <span class="nilai"><?php echo $persentaseDiskon; ?>%</span></p>
    <p><span class="label">Diskon</span> <span class="nilai">Rp <?php echo number_format($diskon,0,',','.'); ?></span></p>
    <p><span class="label">Total Bayar Setelah Diskon</span> <span class="nilai">Rp <?php echo number_format($totalBayar,0,',','.'); ?></span></p>
  </div>
  <div class="tombol-container">
    <a class="tombol-kembali" href="kasir.php">Kembali ke Kasir</a>
  </div>
</body>
</html>
