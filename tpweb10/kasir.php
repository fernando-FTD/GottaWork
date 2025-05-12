<?php
session_start();
function hitungDiskon($totalBelanja){
    if($totalBelanja >= 100000){
        $persentaseDiskon=10;
    }elseif($totalBelanja >= 50000){
        $persentaseDiskon=5;
    }else{
        $persentaseDiskon=0;
    }
    $diskon=(int)($totalBelanja*($persentaseDiskon/100));
    $totalBayar=(int)($totalBelanja-$diskon);
    return array($persentaseDiskon,$diskon,$totalBayar);
}
$daftarProduk=[
    "Kacang Disco"=>17000,
    "Pie Susu"=>25000,
    "Kopi Bali"=>45000,
    "Pempek"=>25000,
    "Tekwan"=>18000,
    "Kemplang"=>15000,
    "Bakpia Pathok"=>35000,
    "Brem"=>20000,
    "Wingko Babat"=>40000
];
if(!isset($_SESSION['item'])){
    $_SESSION['item']=[];
    $_SESSION['item'][]=['produk'=>'','jumlah'=>1];
}
$totalBelanja=0;
$diskon=0;
$persentaseDiskon=0;
$totalBayar=0;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['produk'])&& isset($_POST['jumlah'])){
        $sementaraItem=[];
        $produkTerkirim=$_POST['produk'];
        $jumlahTerkirim=$_POST['jumlah'];
        for($i=0; $i < count($produkTerkirim); $i++){
            $sementaraItem[]=[
                'produk'=>$produkTerkirim[$i],
                'jumlah'=>intval($jumlahTerkirim[$i])
            ];
        }
        $_SESSION['item']=$sementaraItem;
    }
    if(isset($_POST['hapus'])){
        $indeks=intval($_POST['hapus']);
        if(count($_SESSION['item'])> 1){
            array_splice($_SESSION['item'],$indeks,1);
        }
    }elseif(isset($_POST['reset'])){
        $_SESSION['item']=[];
        $_SESSION['item'][]=['produk'=>'','jumlah'=>1];
    }elseif(isset($_POST['tambah'])){
        $_SESSION['item'][]=['produk'=>'','jumlah'=>1];
    }elseif(isset($_POST['hitung'])){
    }elseif(isset($_POST['selesai'])){
        foreach($_SESSION['item'] as $item){
            $produk=$item['produk'];
            $jml=$item['jumlah'];
            if($produk != '' && isset($daftarProduk[$produk])){
                $totalBelanja += $daftarProduk[$produk]*$jml;
            }
        }
        list($persentaseDiskon,$diskon,$totalBayar)=hitungDiskon($totalBelanja);
        $tanggalTransaksi=date("Y-m-d H:i:s");
        $koneksi=new mysqli("localhost","root","","tpweb10");
        $stmt=$koneksi->prepare("INSERT INTO belanja(total_belanja,diskon,total_bayar,diskon_persen,tanggal_transaksi)VALUES(?,?,?,?,?)");
        $stmt->bind_param("iiiis",$totalBelanja,$diskon,$totalBayar,$persentaseDiskon,$tanggalTransaksi);
        $stmt->execute();
        $stmt->close();
        $koneksi->close();
        $_SESSION['hasil']=[
            'totalBelanja'=>$totalBelanja,
            'persentaseDiskon'=> $persentaseDiskon,
            'diskon'=>$diskon,
            'totalBayar'=>$totalBayar
        ];
        header("Location: struk.php");
        exit;
    }
    $totalBelanja=0;
    foreach($_SESSION['item'] as $item){
        $produk=$item['produk'];
        $jml=$item['jumlah'];
        if($produk != '' && isset($daftarProduk[$produk])){
            $totalBelanja += $daftarProduk[$produk]*$jml;
        }
    }
    list($persentaseDiskon,$diskon,$totalBayar)=hitungDiskon($totalBelanja);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kasir Toko Healing</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="kasir-box">
    <h2>Kasir Toko Healing</h2>
    <form action="kasir.php" method="post">
      <table id="tabel-belanja">
        <thead>
          <tr>
            <th style="text-align:center;">Nama Oleh-oleh</th>
            <th style="text-align:center;">Harga</th>
            <th style="text-align:center;">Jumlah(pcs)</th>
            <th style="text-align:center;">Total Harga</th>
            <th style="text-align:center;">Hapus</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($_SESSION['item'] as $indeks=>$item): ?>
          <tr>
            <td>
              <select name="produk[]">
                <option value="">---- pilih oleh-oleh ----</option>
                <?php foreach($daftarProduk as $produk=>$harga): ?>
                <option value="<?php echo $produk; ?>" <?php echo($item['produk'] == $produk)? 'selected' : ''; ?>>
                  <?php echo $produk; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td style="text-align:right;">
              <?php 
                if($item['produk'] !== '' && isset($daftarProduk[$item['produk']])){
                    echo "Rp " . number_format($daftarProduk[$item['produk']],0,',','.');
                }else{
                    echo "-";
                }
              ?>
            </td>
            <td>
              <input type="number" name="jumlah[]" value="<?php echo $item['jumlah']; ?>" min="1">
            </td>
            <td style="text-align:right;">
              <?php 
                if($item['produk'] !== '' && isset($daftarProduk[$item['produk']])){
                    echo "Rp " . number_format($daftarProduk[$item['produk']]*$item['jumlah'],0,',','.');
                }else{
                    echo "-";
                }
              ?>
            </td>
            <td style="text-align:center;">
              <?php if(count($_SESSION['item'])> 1): ?>
                <button type="submit" name="hapus" value="<?php echo $indeks; ?>">Hapus</button>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="tombol-container">
        <button type="submit" name="reset" class="tombol">Reset Semua</button>
        <button type="submit" name="tambah" class="tombol">+ Tambah Barang</button>
        <button type="submit" name="hitung" class="tombol">Hitung</button>
        <button type="submit" name="selesai" class="tombol">SELESAI</button>
      </div>
    </form>
    <div class="total-area">
      <p>Total Belanja = <span id="total-belanja">Rp <?php echo number_format($totalBelanja,0,',','.'); ?></span></p>
      <p>Diskon = <span id="diskon">Rp <?php echo number_format($diskon,0,',','.'); ?></span> (<span id="diskon-persentase"><?php echo $persentaseDiskon; ?>%</span>)</p>
      <p><strong>Total Bayar = <span id="total-bayar">Rp <?php echo number_format($totalBayar,0,',','.'); ?></span></strong></p>
    </div>
  </div>
  <script>
  document.addEventListener("DOMContentLoaded",function(){
      document.addEventListener("keydown",function(event){
          if(event.key === "Enter"){
              event.preventDefault();
          }
      });
  });
  </script>
</body>
</html>
