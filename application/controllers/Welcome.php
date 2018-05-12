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
          $this->load->helper(array('url'));
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
			
			$data = array(
				'id_jurnal' => $key->id_jurnal,
				'id_direktori' => $key->id_direktori,
				'title' => $titleRemove,
				'abstrak' => $abstractRemove,
				'keywords' => $keywordRemove
			);
			
			/*
			$titleSplit = explode(" ", $titleRemove);
			$keywordSplit = explode(" ", $keywordRemove);
			$abstractSplit = explode(" ", $abstractRemove);

			$titleClear = array_unique($titleSplit);
			$keywordClear = array_unique($keywordSplit);
			$abstractClear = array_unique($abstractSplit);

			sort($titleClear);
			sort($keywordClear);
			sort($abstractClear);

			$jsontitle = json_encode($titleClear);
			$jsonkeyword = json_encode($keywordClear);
			$jsonabstrak = json_encode($abstractClear);
			$data = array(
				'id_jurnal' => $key->id_jurnal,
				'title' => $jsontitle,
				'abstrak' => $jsonabstrak,
				'keywords' => $jsonkeyword
			);
			*/
			$this->data->insertStemmer($data);
			//$datapdf[$key->id_jurnal] = array($titleClear,$abstractClear,$keywordClear);
			
		}
		//return $datapdf;
	}

	public function pdfstemmer(){
		$artikel = $this->data->getStemmer();
		foreach ($artikel->result() as $key) {

			$titleSplit = explode(" ", $key->title);
			$keywordSplit = explode(" ", $key->keywords);
			$abstractSplit = explode(" ", $key->abstrak);

			$titleClear = array_unique($titleSplit);
			$keywordClear = array_unique($keywordSplit);
			$abstractClear = array_unique($abstractSplit);

			sort($titleClear);
			sort($keywordClear);
			sort($abstractClear);

			//$titleClear = json_decode($key->title);
			//$abstractClear = json_decode($key->abstrak);
			//$keywordClear = json_decode($key->keywords);

			$datapdf[$key->id_jurnal] = array($titleClear,$abstractClear,$keywordClear);
		}
			return $datapdf;
	}

	public function stepthree($data){ //abstract user dibandingkan dengan judul, abstract dan keyword pada artikel
		//$datapdf = $this->pdfstemmer();
		$artikel = $this->data->getStemmer();
		foreach ($artikel->result() as $key) {
			
			$titleSplit = explode(" ", $key->title);
			$keywordSplit = explode(" ", $key->keywords);
			$abstractSplit = explode(" ", $key->abstrak);

			$titleClear = array_unique($titleSplit);
			$keywordClear = array_unique($keywordSplit);
			$abstractClear = array_unique($abstractSplit);

			sort($titleClear);
			sort($keywordClear);
			sort($abstractClear);

			$absTitle = 0;
			$absAbstract = 0;
			$absKeyword = 0;
			$keyTitle = 0;
			$keyAbstract = 0;
			$keyKeyword = 0;
			$bidTitle = 0;
			$bidAbstract = 0;
			$bidKeyword = 0;

			for($x=0;$x<count($data);$x++){
				if($x==1){
					for($y=0;$y<3;$y++){
						if ($y==0) { // abstrak dengan judul
							$absTitle = array_intersect($data[$x], $titleClear);
							$arr_union = array_merge($data[$x], $titleClear);
							//$totAbsTitle = (count($absTitle) / (count($arr_union)))*2; jaccard algorithm
							$totAbsTitle = count($absTitle) / (sqrt(count($data[$x])) * sqrt(count($titleClear))); //cosine
						}elseif ($y==1) { // abstrak dengan abstrak
							$absAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x],$abstractClear);
							//$totAbsAbstract = (count($absAbstract) / (count($arr_union)))*2; jaccard algorithm
							$totAbsAbstract = count($absAbstract) / (sqrt(count($data[$x])) * sqrt(count($abstractClear))); //cosine
						}elseif ($y==2) { // abstrak dengan keyword
							$absKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x],$keywordClear);
							//$totAbsKeyword = (count($absKeyword) / (count($arr_union)))*2; jaccard algorithm
							$totAbsKeyword = count($absKeyword) / (sqrt(count($data[$x])) * sqrt(count($keywordClear))); //cosine
						}
					}
				}

				if($x==2){
					for($y=0;$y<3;$y++){
						if ($y==0) { // keyword dengan judul
							$keyTitle = array_intersect($data[$x], $titleClear);
							$arr_union = array_merge($data[$x], $titleClear);
							//$totKeyTitle = (count($keyTitle) / (count($arr_union)))*2; jaccard algorithm
							$totKeyTitle = count($keyTitle) / (sqrt(count($data[$x])) * sqrt(count($titleClear))); //cosine
						}elseif ($y==1) { // keyword dengan abstrak
							$keyAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x], $abstractClear);
							//$totKeyAbstract = (count($keyAbstract) / (count($arr_union)))*2; jaccard algorithm
							$totKeyAbstract = count($keyAbstract) / (sqrt(count($data[$x])) * sqrt(count($abstractClear))); //cosine
						}elseif ($y==2) { // keyword dengan keyword
							$keyKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x],$keywordClear);
							//$totKeyKeyword = (count($keyKeyword) / (count($arr_union)))*2; jaccard algorithm
							$totKeyKeyword = count($keyKeyword) / (sqrt(count($data[$x])) * sqrt(count($keywordClear))); //cosine
						}
					}
				}

				if($x==3){
					for($y=0;$y<3;$y++){
						if ($y==0) { // bidang dengan judul
							$bidTitle = array_intersect($data[$x],$titleClear);
							$arr_union = array_merge($data[$x],$titleClear);
							//$totBidTitle = (count($bidTitle) / (count($arr_union)))*2; jaccard algorithm
							$totBidTitle = count($bidTitle) / (sqrt(count($data[$x])) * sqrt(count($titleClear))); //cosine 
						}elseif ($y==1) { // bidang dengan abstrak
							$bidAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x],$abstractClear);
							//$totBidAbstract = (count($bidAbstract) / (count($arr_union)))*2; jaccard algorithm
							$totBidAbstract = count($bidAbstract) / (sqrt(count($data[$x])) * sqrt(count($abstractClear))); //cosine
						}elseif ($y==2) { // bidang dengan keyword
							$bidKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x], $keywordClear);
							//$totBidKeyword = (count($bidKeyword) / (count($arr_union)))*2; jaccard algorithm
							$totBidKeyword = count($bidKeyword) / (sqrt(count($data[$x])) * sqrt(count($keywordClear)));//cosine

						}
					}
				}
			}
			$totalstepthree[$key->id_jurnal] = (($totAbsTitle*0) + ($totAbsAbstract * 100) + ($totAbsKeyword * 0 ));
			$totalstepfour[$key->id_jurnal] = (($totKeyTitle*10)+($totKeyAbstract*10) + ($totKeyKeyword * 80));
			$totalstepfive[$key->id_jurnal] = (($totBidTitle*30)+($totBidAbstract*20) + ($totBidKeyword * 50));
			$totalallstep[$key->id_jurnal] = (($totalstepthree[$key->id_jurnal]*80)+($totalstepfour[$key->id_jurnal]*10)+($totalstepfive[$key->id_jurnal])*10)/100;

			$finaltotalallstep[$key->id_direktori] = 0;
		}
		$cosine = array($totalallstep,$finaltotalallstep,$totalstepthree,$totalstepfour, $totalstepfive);
		return $cosine;
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

		$cosine = $this->stepthree($data);

		$artikel = $this->data->getStemmer();
		foreach ($artikel->result() as $key) {
			if($cosine[1][$key->id_direktori] == 0){
				$cosine[1][$key->id_direktori] = $cosine[0][$key->id_jurnal];
				//$allstep[$key->id_direktori] = array($cosine[2],$cosine[3],$cosine[4]);
			}else{
				$cosine[1][$key->id_direktori] = ($cosine[1][$key->id_direktori] + $cosine[0][$key->id_jurnal])/2;
			}
		}

		arsort($cosine[1]);
		
		foreach ($cosine[1] as $key => $val ) {
			if($val >=1){
				$jurnal= $this->data->getJurnal($key);
				$datafinal[$key] = array(
					'judul' => $jurnal['judul'],
					'editor' => $jurnal['editor'],
					'penerbit' => $jurnal['penerbit'],
					'alamat' => $jurnal['alamat'],
					'deskriptor' => $jurnal['deskriptor'],
					'match' => $val,
				);
			}
		}

		$datafix['data'] = $datafinal;
		$this->load->view('template/header');
   		$this->load->view('pages/result',$datafix);
   		$this->load->view('template/footer');
		/*
		foreach ($artikel->result() as $key) {
			if($cosine[1][$key->id_direktori]>=1){
				$jurnal= $this->data->getJurnal($key->id_direktori);
				$datafinal[$key->id_direktori] = array(
					'judul' => $jurnal['judul'],
					'editor' => $jurnal['editor'],
					'penerbit' => $jurnal['penerbit'],
					'alamat' => $jurnal['alamat'],
					'deskriptor' => $jurnal['deskriptor'],
					'match' => $cosine[1][$key->id_direktori],
					'totalstep' => array_push($datafinal[$key->id_direktori]['totalstep'], $cosine[0][$key->id_jurnal])
				);
			}
		}
		
		echo "<pre>";
		print_r($datafinal[1]['totalstep']);
		echo "</pre>";
		*/
   	}

}
