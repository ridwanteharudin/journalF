<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct() {
          parent::__construct();
          $this->load->model(array('data'));

    }
	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('pages/content');
		$this->load->view('template/footer');
	}

	public function pdf(){
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();

		$stopword = $stopwordFactory->createStopWordRemover();
        $stemmer  = $stemmerFactory->createStemmer();
		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$judulpdf = $key -> judul;
			$abstrakpdf = $key -> abstrak;
			$keywordpdf = $key -> keyword;
			$bidangpdf = $key -> bidang;


			$judulpdfstem = $stemmer->stem($judulpdf);
			$abstrakpdfstem = $stemmer->stem($abstrakpdf);
			$keywordpdfstem = $stemmer->stem($keywordpdf);
			$bidangpdfstem = $stemmer->stem($bidangpdf);

			$judulpdffinal = $stopword->remove($judulpdfstem);
			$abstrakpdffinal = $stopword->remove($abstrakpdfstem);
			$keywordpdffinal = $stopword->remove($keywordpdfstem);
			$bidangpdffinal = $stopword->remove($bidangpdfstem);

			$judulpdfsplit = explode(" ", $judulpdffinal);
			$abstrakpdfsplit = explode(" ", $abstrakpdffinal);
			$keywordpdfsplit = explode(" ", $keywordpdffinal);
			$bidangpdfsplit = explode(" ", $bidangpdffinal);

			$judulpdfclear = array_unique($judulpdfsplit);
			$abstrakpdfclear = array_unique($abstrakpdfsplit);
			$keywordpdfclear = array_unique($keywordpdfsplit);
			$bidangpdfclear = array_unique($bidangpdfsplit);

			sort($judulpdfclear);
			sort($abstrakpdfclear);
			sort($keywordpdfclear);
			sort($judulpdfclear);

			$datapdf[$key->id] = array($judulpdfclear,$abstrakpdfclear,$keywordpdfclear,$bidangpdfclear);
		}
		return $datapdf;
	}
	public function stepone($data){
		$datapdf = $this->pdf();

		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$jujul = 0;
			$abab = 0;
			$wordword = 0;
			$bidbid = 0;
			for($x=0;$x<count($data);$x++){
				if($x==0){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==0) { // judul dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$jujul = $jujul + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasiljul = ($jujul/count($data[$x])) * 100;
							}else{
								$hasiljul = ($jujul/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}elseif ($x==1) {
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==1) { //abstrak dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$abab = $abab + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilab = ($abab/count($data[$x])) * 100;
							}else{
								$hasilab = ($abab/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}elseif ($x==2) {
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==2) { //keyword dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != "" ){
										$wordword = $wordword + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilword = ($wordword/count($data[$x])) * 100;
							}else{
								$hasilword = ($wordword/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}elseif($x==3){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==3) { //bidang dengan bidang
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != "" ){
										$bidbid = $bidbid + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilbid = ($bidbid/count($data[$x])) * 100;
							}else{
								$hasilbid = ($bidbid/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}

			}
			//$hasil[$key->id] = $jujul + $abab + $biword;
			$totalstepone[$key->id] = (($hasiljul * 30) + ($hasilab * 30) + ($hasilword * 20) + ($hasilbid * 20))/100;	
		}
		
		return $totalstepone;
	}

	public function steptwo($data){ //judul user dibandingkan dengan seluruh pdf kecuali judulnya
		$datapdf = $this->pdf();

		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$jutrak = 0;
			$juword = 0;
			$jubid = 0;
			for($x=0;$x<count($data);$x++){
				if($x==0){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==1) { // judul dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$jutrak = $jutrak + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasiljutrak = ($jutrak/count($data[$x])) * 100;
							}else{
								$hasiljutrak = ($jutrak/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==2) { // judul dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$juword = $juword + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasiljuword = ($juword/count($data[$x])) * 100;
							}else{
								$hasiljuword = ($juword/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==3) { // judul dengan bidang
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$jubid = $jubid + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasiljubid = ($jubid/count($data[$x])) * 100;
							}else{
								$hasiljubid = ($jubid/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}
			}
			$totalsteptwo[$key->id] = (($hasiljutrak*50)+($hasiljuword*30) + ($hasiljubid*20))/100;	
		}
		return $totalsteptwo;
	}
	public function stepthree($data){ //judul user dibandingkan dengan seluruh pdf kecuali judulnya
		$datapdf = $this->pdf();

		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$abjul = 0;
			$abword = 0;
			$abbid = 0;
			for($x=0;$x<count($data);$x++){
				if($x==1){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==0) { // abtrak dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$abjul = $abjul + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilabjul = ($abjul/count($data[$x])) * 100;
							}else{
								$hasilabjul = ($abjul/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==2) { // abstrak dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$abword = $abword + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilabword = ($abword/count($data[$x])) * 100;
							}else{
								$hasilabword = ($abword/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==3) { // abstrak dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$abbid = $abbid + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilabbid = ($abbid/count($data[$x])) * 100;
							}else{
								$hasilabbid = ($abbid/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}
			}
			$totalstepthree[$key->id] = (($hasilabjul*50) + ($hasilabword * 30) + ($hasilabbid * 20 ))/100;
		}
		return $totalstepthree;
	}
	public function stepfour($data){ //judul user dibandingkan dengan seluruh pdf kecuali judulnya
		$datapdf = $this->pdf();

		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$wordjul = 0;
			$wordtrak = 0;
			$wordbid = 0;
			for($x=0;$x<count($data);$x++){
				if($x==2){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==0) { // keyword dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$wordjul = $wordjul + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilwordjul = ($wordjul/count($data[$x])) * 100;
							}else{
								$hasilwordjul = ($wordjul/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==1) { // keyword dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$wordtrak = $wordtrak + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilwordtrak = ($wordtrak/count($data[$x])) * 100;
							}else{
								$hasilwordtrak = ($wordtrak/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==3) { // keyword dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$wordbid = $wordbid + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilwordbid = ($wordbid/count($data[$x])) * 100;
							}else{
								$hasilwordbid = ($wordbid/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}
			}
			$totalstepfour[$key->id] = (($hasilwordjul*50)+($hasilwordtrak*30) + ($hasilwordbid * 20)) / 100;	
		}
		return $totalstepfour;
	}

	public function stepfive($data){ //judul user dibandingkan dengan seluruh pdf kecuali judulnya
		$datapdf = $this->pdf();

		$jurnal = $this->data->getData();
		foreach ($jurnal->result() as $key) {
			$bijul = 0;
			$bitrak = 0;
			$biword = 0;
			for($x=0;$x<count($data);$x++){
				if($x==3){
					for($y=0;$y<count($datapdf[$key->id]);$y++){
						if ($y==0) { // bidang dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$bijul = $bijul + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilbijul = ($bijul/count($data[$x])) * 100;
							}else{
								$hasilbijul = ($bijul/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==1) { // bidang dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$bitrak = $bitrak + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilbitrak = ($bitrak/count($data[$x])) * 100;
							}else{
								$hasilbitrak = ($bitrak/count($datapdf[$key->id][$y]))*100;
							}
						}elseif ($y==2) { // bidang dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$biword = $biword + 1;
									}
								}
							}
							if(count($data[$x])>count($datapdf[$key->id][$y])){
								$hasilbiword = ($biword/count($data[$x])) * 100;
							}else{
								$hasilbiword = ($biword/count($datapdf[$key->id][$y]))*100;
							}
						}
					}
				}
			}
			$totalstepfive[$key->id] = (($hasilbijul*50)+($hasilbitrak*30) + ($hasilbiword * 20)) / 100;	
		}
		return $totalstepfive;
	}

	public function input_data(){
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();

		$stopword = $stopwordFactory->createStopWordRemover();
        $stemmer  = $stemmerFactory->createStemmer();

		$judul = $this->input->post('judul');
		$abstrak = $_POST['abstrak'];
		$keyword = $_POST['keyword'];
		if(!empty($_POST['bidang']))
			$bidangclear = $_POST['bidang'];
		else
			$bidangclear = " ";

		$judulstem = $stemmer->stem($judul);
		$abstrakstem =$stemmer->stem($abstrak);
		$keywordstem = $stemmer->stem($keyword);

		$judulremove = $stopword->remove($judulstem);
		$abstrakremove = $stopword->remove($abstrakstem);
		$keywordremove = $stopword->remove($keywordstem);

		$judulsplit = explode(" ", $judulremove);
		$abstraksplit = explode(" ", $abstrakremove);
		$keywordsplit = explode(" ", $keywordremove);

		$judulclear = array_unique($judulsplit);
		$abstrakclear = array_unique($abstraksplit);
		$keywordclear = array_unique($keywordsplit);

		sort($judulclear);
		sort($abstrakclear);
		sort($keywordclear);
	
		$data = array($judulclear,$abstrakclear,$keywordclear, $bidangclear);

		$stepone = $this->stepone($data);
		$steptwo = $this->steptwo($data);
		$stepthree = $this->stepthree($data);
		$stepfour = $this->stepfour($data);
		$stepfive = $this->stepfive($data);

		$artikel = $this->data->getData();
		
		foreach ($artikel->result() as $key) { //untuk menjumlahkan seluruh step dan dibuat rata-rata
			$totalallstep[$key->id] = (($stepone[$key->id]*30)+($steptwo[$key->id]*20)+($stepthree[$key->id]*20)+($stepfour[$key->id]*10)+($stepfive[$key->id])*20)/100;
			$finaltotalallstep[$key->id_jurnal] = 0;
		}
		$inc = 0;
		foreach ($artikel->result() as $key) {
			if($finaltotalallstep[$key->id_jurnal] == 0){
				$finaltotalallstep[$key->id_jurnal] = $totalallstep[$key->id];
			}else{
				$finaltotalallstep[$key->id_jurnal] = ($finaltotalallstep[$key->id_jurnal] + $totalallstep[$key->id])/2;
			} 
		}
		
		arsort($finaltotalallstep);
		print_r($finaltotalallstep);
		echo "<br>";
		
		foreach ($finaltotalallstep as $key => $val ) {
			$judulfinal= $this->data->getJudulJurnal($key);
			echo $key." <a href='google.com'>".$judulfinal['judul_jurnal']."</a>";
			echo "<br>";
		}


		//echo $finaltotalallstep[1]." %";
		echo "<br>";
		//echo $finaltotalallstep[2]. " %"; 

		/*
		if(empty($judul)){
			if(empty($abstrak)){
				$data = array($bidangclear);
			}elseif (empty($bidang)) {
				$data = array($abstrakclear);
			}elseif (empty($bidang) && empty($abstrak)) {
				$data = null;
			}else{
				$data = array($abstrakclear,$bidangclear);
			}
		}elseif (empty($abstrak)) {
			if(empty($judul)){
				$data = array($bidangclear);
			}elseif (empty($bidang)) {
				$data = array($judulclear);
			}elseif (empty($bidang) && empty($judul)) {
				$data = null;
			}else{
				$data = array($judulclear,$bidangclear);
			}
		}elseif (empty($bidang)) {
			if(empty($judul)){
				$data = array($abstrakclear);
			}elseif (empty($abstrak)) {
				$data = array($judulclear);
			}elseif (empty($abstrak) && empty($judul)) {
				$data = null;
			}else{
				$data = array($judulclear,$abstrakclear);
			}
		}else{
			$data = array($judulclear,$abstrakclear,$bidangclear );
		}



		ambil data didatabase
		$jurnal = $this->data->getData();
		$jujul = 0;
		$jutrak = 0;
		$juword = 0;
		$abjul = 0;
		$abab = 0;
		$abword = 0;
		$bijul = 0;
		$bitrak = 0;
		$biword = 0;
		$counter=0;
		foreach ($jurnal->result() as $key) {
			$jujul = 0;
			$jutrak = 0;
			$juword = 0;
			$abjul = 0;
			$abab = 0;
			$abword = 0;
			$bijul = 0;
			$bitrak = 0;
			$biword = 0;
			$counter=0;
			$judulpdf = $key -> judul;
			$abstrakpdf = $key -> abstrak;
			$keywordpdf = $key -> keyword;

			$judulpdfstem = $stemmer->stem($judulpdf);
			$abstrakpdfstem = $stemmer->stem($abstrakpdf);
			$keywordpdfstem = $stemmer->stem($keywordpdf);

			$judulpdffinal = $stopword->remove($judulpdfstem);
			$abstrakpdffinal = $stopword->remove($abstrakpdfstem);
			$keywordpdffinal = $stopword->remove($keywordpdfstem);

			$judulpdfsplit = explode(" ", $judulpdffinal);
			$abstrakpdfsplit = explode(" ", $abstrakpdffinal);
			$keywordpdfsplit = explode(" ", $keywordpdffinal);

			$judulpdfclear = array_unique($judulpdfsplit);
			$abstrakpdfclear = array_unique($abstrakpdfsplit);
			$keywordpdfclear = array_unique($keywordpdfsplit);

			$datapdf = array($judulpdfclear,$abstrakpdfclear,$keywordpdfclear);
			for($x=0;$x<count($data);$x++){
				if($x==0){
					for($y=0;$y<count($datapdf);$y++){
						if ($y==0) { // judul dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$jujul = $jujul + 1;
									}
								}
							}
						}elseif ($y==1) { // judul dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$jutrak = $jutrak + 1;
									}
								}
							}
						}elseif ($y==2) { // judul dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$juword = $juword + 1;
									}
								}
							}
						}
					}
				}elseif ($x==1) {
					for($y=0;$y<3;$y++){
						if ($y==0) { // abstrak dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$abjul = $abjul + 1;
									}
								}
							}
						}elseif ($y==1) { //abstrak dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$abab = $abab + 1;
									}
								}
							}
						}elseif ($y==2) { //abstrak dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$abword = $abword + 1;
									}
								}
							}
						}
					}
				}elseif ($x==2) {
					for($y=0;$y<3;$y++){
						if ($y==0) { // bidang dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$bijul = $bijul + 1;
									}
								}
							}
						}elseif ($y==1) { // bidang dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$bitrak = $bitrak + 1;
									}
								}
							}
						}elseif ($y==2) { //bidang dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$y]);$m++){
									if($data[$x][$z]==$datapdf[$y][$m]){
										$biword = $biword + 1;
									}
								}
							}
						}
					}
				}
			}
			$hasil[$key->id] = $jujul + $jutrak + $juword + $abjul + $abab + $abword + $bijul + $bitrak + $biword;
			echo $hasil[$key->id];
			echo "<br>";
		}
		sort($hasil);
		print_r($hasil); */
   } 
}
