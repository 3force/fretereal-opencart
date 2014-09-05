catalog/model/checkout/order.php

// Bloco Frete Real
		if (isset($_SESSION['fretereal'])) {
			$comentario = "Token do cálculo: " . $_SESSION['fretereal']['calculo']['token'] . "<br/>";

			foreach ($_SESSION['fretereal']['calculo']['caixas'] as $key => $value) {
				$comentario .= "Caixa #" . ($key + 1) . ":<br/>";
            	$comentario .= "Comprimento: ".number_format($value['c'],2)."cm<br/>";
            	$comentario .= "Largura: ".number_format($value['l'],2)."cm<br/>";
            	$comentario .= "Altura: ".number_format($value['a'],2)."cm<br/>";
            	$comentario .= "Peso: ".number_format($value['peso'],2,",",".")."kg<br/>";
            	$comentario .= "Valor: R$".number_format($value['valor'],2,",",".")."<br/>";
            	$comentario .= "Produtos da caixa: <br/>";
            	foreach ($value['produto'] as $pNome => $pQtd) {
            		$comentario .= $pQtd."x ".$pNome."<br/>";
            	}

            	if (($key+1) < count($_SESSION['fretereal']['calculo']['caixas'])) {
            		$comentario .= "<hr/>";
            	}
			}
			$_SESSION['fretereal']['calculo'] = false;
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history (order_id, order_status_id, notify, comment, date_added) VALUES (".(int)$order_id.", 1, 0, '".$comentario."', '".date("Y-m-d H:i:s")."');");
		}
