<?php

namespace monetizze;

class Roleta
{

	private $quantidadeDezenas;
	private $resultado;
	private $totalJogos;
	private $jogos;
	private $valoresPermitidosQuantidadeDezenas = [6, 7, 8, 9, 10];

	function __construct($quantidadeDezenas, $totalJogos)
	{
		$this
			->setQuantidadeDezenas($quantidadeDezenas)
			->setTotalJogos($totalJogos);
	}


	public function getQuantidadeDezenas()
	{

		return $this->quantidadeDezenas;
	}

	public function setQuantidadeDezenas($quantidadeDezenas)
	{

		if (!in_array($quantidadeDezenas, $this->valoresPermitidosQuantidadeDezenas))
			throw new \Exception('Dezena inválida!');

		$this->quantidadeDezenas = $quantidadeDezenas;

		return $this;
	}

	public function getResultado()
	{
		return $this->resultado;
	}

	public function setResultado($resultado)
	{
		$this->resultado = $resultado;
		return $this;
	}

	public function getTotalJogos()
	{
		return $this->totalJogos;
	}

	public function setTotalJogos($totalJogos)
	{

		$this->totalJogos = $totalJogos;
		return $this;
	}

	public function getJogos()
	{
		return $this->jogos;
	}

	public function setjogos($jogos)
	{
		$this->jogos = $jogos;
		return $this;
	}

	private function gerarJogo()
	{

		$todasDezenas = range(1, 60);
		$jogo = [];
		for ($i = 0; $i < $this->getQuantidadeDezenas(); $i++) {

			$totalPossibilidades = count($todasDezenas) - 1;
			$indice = rand(0, $totalPossibilidades);
			$jogo[] = $todasDezenas[$indice];
			unset($todasDezenas[$indice]);
			$todasDezenas = array_values($todasDezenas);

		}
		sort($jogo);
		return $jogo;
	}

	public function gerarJogos()
	{

		$jogos = [];

		for ($i = 0; $i < $this->getTotalJogos(); $i++) {
			$jogos[] = $this->gerarJogo();
		}

		$this->setJogos($jogos);
	}


	public function gerarSorteio()
	{
		$sorteio = [];

		$sorteio = $this->gerarJogo();

		$this->setResultado($sorteio);
	}

	public function exibirResultado()
	{	

		$cabecalho = '';
		for ($i = 1; $i <= $this->getQuantidadeDezenas(); $i++) {
			$cabecalho .= <<< HTML
				<th> Coluna {$i}</th>
			HTML;
		}


		$corpoJogos = '';
		
		foreach ($this->getJogos() as $jogo) {

			$acertos = 0;

			$corpoJogos .= <<< HTML
				<tr>
					<td> JOGO </td>
			HTML;

			for ($i = 0; $i < $this->getQuantidadeDezenas(); $i++) {

				if(in_array($jogo[$i], $this->getResultado())){
					$classe = "text-success";
					++ $acertos;
				}else{
					$classe = "text-danger";
				}

				$corpoJogos .= <<< HTML
					<td class='{$classe}'> {$jogo[$i]} </td>
				HTML;
			}
			

			$corpoJogos .=  <<< HTML
				<td>{$acertos}</td>
				</tr>
			HTML;
		}


		$corpoResultado = '';
		$corpoResultado .= <<< HTML
				<tr>
					<td> RESULTADO </td>
			HTML;

		for ($i = 0; $i < $this->getQuantidadeDezenas(); $i++) {

			$corpoResultado .= <<< HTML
					<td> {$this->getResultado()[$i]} </td>
				HTML;
		}

		$corpoResultado .=  <<< HTML
				</tr>
			HTML;


		$html = <<< HTML
		<table class='table'>
			<tr>
				<th> Tipo </th>
				$cabecalho
				<th>Acertos</th>
				</tr>
			<tbody>
				{$corpoResultado}
				{$corpoJogos}
			</tbody>	
		</table>
		HTML;

		return $html;
	}
}
