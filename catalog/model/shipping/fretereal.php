<?php
class ModelShippingFreteReal extends Model {
	function getQuote($address) {
		$this->language->load('shipping/fretereal');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('fretereal_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('fretereal_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$hash = "";

			// Dados de acesso a API do Frete Real
            $client_id = $this->config->get('fretereal_client_id');
            $client_secret = $this->config->get('fretereal_client_secret');
            $mao_propria = $this->config->get('fretereal_own_hands');
            $aviso_recebimento = $this->config->get('fretereal_receive_alert');
            // $valor_declarado = "0";
            $extra_days = $this->config->get('fretereal_extra_days');
            // $peso = $this->data['weight'] = $product_info['weight'];

            // Verifica se esta na sessao os dados dos fretes
			if (!isset($_SESSION['fretereal']) || !isset($_SESSION['fretereal']['fretes'])) {
	        	$dadosParaAPI = array(
	        		'client_id' => $client_id,
	        		'client_secret' => $client_secret
	    		);

	        	$caminhoUrl = "https://fretereal.com/oauth/";
	            $caminhoApi = $caminhoUrl . "action/getFretes";
	            $caminhoToken = $caminhoUrl . "action/request_token";

	            $curl = curl_init($caminhoToken);
	            curl_setopt($curl, CURLOPT_POST, true);
	            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
	                'client_id' => $client_id,
	                'client_secret' => $client_secret,
	                'grant_type' => 'client_credentials'
	                )
	            );

	            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	            $retToken = curl_exec($curl);

	            $auth = json_decode($retToken);
	            $access_key = $auth->access_token;

	            $curl2 = curl_init($caminhoApi . "?access_token=" . $access_key);
	            curl_setopt($curl2, CURLOPT_POST, true);
	            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($dadosParaAPI));
	            curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
	            $ret = curl_exec($curl2);
	            $ret = json_decode($ret, true);

	            if ($ret['status'] == 1) {
	            	$return = array(
		            	'label' => "Frete Real",
		            	'value' => array()
		        	);

		        	foreach ($ret['fretes'] as $key => $value) {
		        		$checked = ($this->config->get("fretereal_" . $value['codfrete']) ? true : false);
		        		$return['value'][] = array('value' => $value['codfrete'], 'label' => $value['descricao'], 'check' => $checked);
		        	}

		        	$_SESSION['fretereal']['fretes'] = $return;
	            } else {
	            	$_SESSION['fretereal']['fretes'] = false;
	            }
            }

            $fretes = array();
            $fretesNome = array();
            // Tratamento da sessao
            foreach ($_SESSION['fretereal']['fretes']['value'] as $key => $value) {
                if ($this->config->get("fretereal_" . $value['value'])) {
                	$fretes[] = $value['value'];
                	$fretesNome[$value['value']] = $value['label'];
                }
            }

            if ($client_id == "" || $client_secret == "") {
                return false;
            }

            if ($extra_days == "") {
            	$extra_days = 0;
            }

            // var_dump($_SESSION['fretereal']['dados']);
            if (!isset($this->request->post['postcode'])) {
                $zip = $_SESSION['fretereal']['cep'];
            } else {
                $zip = $this->request->post['postcode'];
            }

            $hash .= $zip;

            $dadosParaAPI = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'cep' => $zip,
                'forma_envio' => implode(",", $fretes),
                'mao_propria' => ($mao_propria ? "S" : "N"),
                'aviso_recebimento' => ($aviso_recebimento ? "S" : "N"),
                'produtos' => array()
            );

            $produtos = $this->cart->getProducts();	

            foreach ($produtos as $key => $prod) {
                // Tratamento de peso
                $pesoFinal = 0;
                $prod['weight'] = ((float) $prod['weight'] / $prod['quantity']);
                switch ($prod['weight_class_id']) {
                    case '1': // KG
                        $pesoFinal = $prod['weight'];
                        break;
                    case '2': // Gramas
                        $pesoFinal = ($prod['weight'] / 1000);
                        break;
                    case '5': // Pounds
                        $pesoFinal = number_format(((float)$prod['weight'] * 453.59237) / 1000,2, ".", "");
                        break;
                    case '6': // Ounce
                        $pesoFinal = number_format(((float)$prod['weight'] * 28.3495231) / 1000,2, ".", "");
                        break;
                }

            	$hash .= $prod['name'].$prod['quantity'];
            	$dadosParaAPI['produtos'][] = array(
            		'nome' => $prod['name'],
                    'comprimento' => $prod['length'],
                    'largura' => $prod['width'],
                    'altura' => $prod['height'],
                    'peso' => $pesoFinal,
                    'qtd' => $prod['quantity']
        		);
            }

            $_SESSION['fretereal']['hash'] = "21312312asdasda";

            // Bate na api para calcular o valor do frete
            if (isset($_SESSION['fretereal']) && $_SESSION['fretereal']['hash'] == $hash) {
                $ret = $_SESSION['fretereal']['calculo'];
            } else {
                $caminhoUrl = "http://fretereal.com/oauth/";
                $caminhoApi = $caminhoUrl . "action/api";
                $caminhoToken = $caminhoUrl . "action/request_token";

                $curl = curl_init($caminhoToken);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'grant_type' => 'client_credentials'
                    )
                );

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $retToken = curl_exec($curl);
                $auth = json_decode($retToken);
                $access_key = $auth->access_token;

                // print_r($dadosParaAPI);

                $curl2 = curl_init($caminhoApi . "?access_token=" . $access_key);
                curl_setopt($curl2, CURLOPT_POST, true);
                curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($dadosParaAPI));
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);

                $ret = curl_exec($curl2);
                // echo $ret."\n\n";
                $ret = json_decode($ret, true);

                $_SESSION['fretereal']['hash'] = $hash;
                $_SESSION['fretereal']['cep'] = $zip;
                $_SESSION['fretereal']['calculo'] = $ret;
                $_SESSION['fretereal']['calculo']['token'] = $access_key;
            }

            $method_data = array(
				'code'       => 'fretereal',
				'title'      => $this->language->get('text_title'),
				'quote'      => array(),
				'sort_order' => $this->config->get('fretereal_sort_order'),
				'error'      => false
			);

            if ($ret['status'] == 1) {
            	foreach ($ret['fretes']['correios'] as $key => $value) {
                    if ($value['status'] == 1) {
    					$quote_data[$value['tipo_frete']] = array(
    						'code'         => 'fretereal.'.$value['tipo_frete'],
    						'title'        => $fretesNome[$value['tipo_frete']] . " (".($value['prazo'] + $extra_days)." dias úteis)" . (isset($value['alerta']) ? "<br/><small>".$value['alerta']."</small>" : ""),
    						'cost'         => $value['valor'],
    						'tax_class_id' => $this->config->get('fretereal_tax_class_id'),
    						'text'         => "R$ ".number_format($value['valor'],2,",",".")
    					);
                    }
            	}
            	$method_data['quote'] = $quote_data;
            } else {
            	$method_data['error'] = $ret['msg'];
            }
		}

		return $method_data;
	}
}
?>