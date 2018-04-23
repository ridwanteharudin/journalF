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
		$artikel = $this->data->getArtikel();
		foreach ($artikel->result() as $key) {
			$title = $key -> title;
			$keyword = $key -> keywords;
			$abstract = $key -> sari;

			$titleStem = $stemmer->stem($title);
			$keywordStem = $stemmer->stem($keyword);
			$abstractStem = $stemmer->stem($abstract);

			$titleRemove = $stopword->remove($titleStem);
			$keywordRemove = $stopword->remove($keywordStem);
			$abstractRemove = $stopword->remove($abstractStem);

			$titleSplit = explode(" ", $titleRemove);
			$keywordSplit = explode(" ", $keywordRemove);
			$abstractSplit = explode(" ", $abstractRemove);

			$titleClear = array_unique($titleSplit);
			$keywordClear = array_unique($keywordSplit);
			$abstractClear = array_unique($abstractSplit);

			sort($titleClear);
			sort($keywordClear);
			sort($abstractClear);

			$datapdf[$key->id_jurnal] = array($titleClear,$abstractClear,$keywordClear);
		}
		return $datapdf;
	}
	/*
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
							$hasiljul = ($jujul / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
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
							$hasilab = ($abab / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
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
							$hasilword = ($wordword / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
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
							$hasilbid = ($bidbid / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
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
							$hasiljutrak = ($jutrak / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
						}elseif ($y==2) { // judul dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$juword = $juword + 1;
									}
								}
							}
							$hasiljuword = ($juword / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
						}elseif ($y==3) { // judul dengan bidang
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id][$y][$m] != ""){
										$jubid = $jubid + 1;
									}
								}
							}
							$hasiljubid = ($jubid / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id][$y]))))*100;
						}
					}
				}
			}
			$totalsteptwo[$key->id] = (($hasiljutrak*50)+($hasiljuword*30) + ($hasiljubid*20))/100;	
		}
		return $totalsteptwo;
	}
	*/

	public function stepthree($data){ //abstract user dibandingkan dengan judul, abstract dan keyword pada artikel
		$datapdf = $this->pdf();
		$artikel = $this->data->getArtikel();
		foreach ($artikel->result() as $key) {
			$absTitle = 0;
			$absAbstract = 0;
			$absKeyword = 0;
			for($x=0;$x<count($data);$x++){
				if($x==1){
					for($y=0;$y<count($datapdf[$key->id_jurnal]);$y++){
						if ($y==0) { // abstrak dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$absTitle = $absTitle + 1;
									}
								}
							}
							$totAbsTitle = ($absTitle / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==1) { // abstrak dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$absAbstract = $absAbstract + 1;
									}
								}
							}
							$totAbsAbstract = ($absAbstract / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==2) { // abstrak dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$absKeyword = $absKeyword + 1;
									}
								}
							}
							$totAbsKeyword = ($absKeyword / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}
					}
				}
			}
			$totalstepthree[$key->id_jurnal] = (($totAbsTitle*0) + ($totAbsAbstract * 100) + ($totAbsKeyword * 0 ))/100;
		}
		return $totalstepthree;
	}
	public function stepfour($data){ //keywords user dibandingkan judul, abstrak dan keyword pada artikel
		$datapdf = $this->pdf();
		$artikel = $this->data->getArtikel();
		foreach ($artikel->result() as $key) {
			$keyTitle = 0;
			$keyAbstract = 0;
			$keyKeyword = 0;
			for($x=0;$x<count($data);$x++){
				if($x==2){
					for($y=0;$y<count($datapdf[$key->id_jurnal]);$y++){
						if ($y==0) { // keyword dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$keyTitle = $keyTitle + 1;
									}
								}
							}
							$totKeyTitle = ($keyTitle / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==1) { // keyword dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$keyAbstract = $keyAbstract + 1;
									}
								}
							}
							$totKeyAbstract = ($keyAbstract / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==2) { // keyword dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$keyKeyword = $keyKeyword + 1;
									}
								}
							}
							$totKeyKeyword = ($keyKeyword / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}
					}
				}
			}
			$totalstepfour[$key->id_jurnal] = (($totKeyTitle*10)+($totKeyAbstract*10) + ($totKeyKeyword * 80)) / 100;	
		}
		return $totalstepfour;
	}
	public function stepfive($data){ //bidang user dibandingkan dengan judul, abstrak dan keyword pada artikel
		$datapdf = $this->pdf();
		$artikel = $this->data->getArtikel();
		foreach ($artikel->result() as $key) {
			$bidTitle = 0;
			$bidAbstract = 0;
			$bidKeyword = 0;
			for($x=0;$x<count($data);$x++){
				if($x==3){
					for($y=0;$y<count($datapdf[$key->id_jurnal]);$y++){
						if ($y==0) { // bidang dengan judul
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$bidTitle = $bidTitle + 1;
									}
								}
							}
							$totBidTitle = ($bidTitle / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==1) { // bidang dengan abstrak
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$bidAbstract = $bidAbstract + 1;
									}
								}
							}
							$totBidAbstract = ($bidAbstract / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}elseif ($y==2) { // bidang dengan keyword
							for($z=0;$z<count($data[$x]);$z++){
								for($m=0;$m<count($datapdf[$key->id_jurnal][$y]);$m++){
									if($data[$x][$z]==$datapdf[$key->id_jurnal][$y][$m] && $data[$x][$z]!= "" && $datapdf[$key->id_jurnal][$y][$m] != ""){
										$bidKeyword = $bidKeyword + 1;
									}
								}
							}
							$totBidKeyword = ($bidKeyword / (sqrt(count($data[$x])) * sqrt(count($datapdf[$key->id_jurnal][$y]))))*100;
						}
					}
				}
			}
			$totalstepfive[$key->id_jurnal] = (($totBidTitle*30)+($totBidAbstract*20) + ($totBidKeyword * 50)) / 100;	
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
		//$stepone = $this->stepone($data);
		//$steptwo = $this->steptwo($data);
		$stepthree = $this->stepthree($data);
		$stepfour = $this->stepfour($data);
		$stepfive = $this->stepfive($data);

		$artikel = $this->data->getArtikel();

		foreach ($artikel->result() as $key) { //untuk menjumlahkan seluruh step dan dibuat rata-rata
			$totalallstep[$key->id_jurnal] = (($stepthree[$key->id_jurnal]*80)+($stepfour[$key->id_jurnal]*10)+($stepfive[$key->id_jurnal])*10)/100;
			$finaltotalallstep[$key->id_direktori] = 0;
		}
	
		foreach ($artikel->result() as $key) {
			if($finaltotalallstep[$key->id_direktori] == 0){
				$finaltotalallstep[$key->id_direktori] = $totalallstep[$key->id_jurnal];
			}else{
				$finaltotalallstep[$key->id_direktori] = ($finaltotalallstep[$key->id_direktori] + $totalallstep[$key->id_jurnal])/2;
			} 
		}
		
		arsort($finaltotalallstep);
		
		foreach ($finaltotalallstep as $key => $val ) {
			if($val != 0){
				$judulfinal= $this->data->getJudulJurnal($key);
				echo $key." <a href='google.com'>".$judulfinal['judul']."</a>";
				echo $judulfinal['deskriptor'];
				echo "<br>";
			}
		}
		//echo $finaltotalallstep[1]." %";
		echo "<br>";
		//echo $finaltotalallstep[2]. " %"; 
   } 
}