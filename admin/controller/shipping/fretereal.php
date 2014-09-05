<?php
class ControllerShippingFreteReal extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('shipping/fretereal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fretereal', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');		
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_next_day_air'] = $this->language->get('text_next_day_air');
		$this->data['text_2nd_day_air'] = $this->language->get('text_2nd_day_air');
		$this->data['text_ground'] = $this->language->get('text_ground');
		$this->data['text_worldwide_express'] = $this->language->get('text_worldwide_express');
		$this->data['text_worldwide_express_plus'] = $this->language->get('text_worldwide_express_plus');
		$this->data['text_worldwide_expedited'] = $this->language->get('text_worldwide_expedited');
		$this->data['text_express'] = $this->language->get('text_express');
		$this->data['text_standard'] = $this->language->get('text_standard');
		$this->data['text_3_day_select'] = $this->language->get('text_3_day_select');
		$this->data['text_next_day_air_saver'] = $this->language->get('text_next_day_air_saver');
		$this->data['text_next_day_air_early_am'] = $this->language->get('text_next_day_air_early_am');
		$this->data['text_expedited'] = $this->language->get('text_expedited');
		$this->data['text_2nd_day_air_am'] = $this->language->get('text_2nd_day_air_am');
		$this->data['text_saver'] = $this->language->get('text_saver');
		$this->data['text_express_early_am'] = $this->language->get('text_express_early_am');
		$this->data['text_express_plus'] = $this->language->get('text_express_plus');
		$this->data['text_today_standard'] = $this->language->get('text_today_standard');
		$this->data['text_today_dedicated_courier'] = $this->language->get('text_today_dedicated_courier');
		$this->data['text_today_intercity'] = $this->language->get('text_today_intercity');
		$this->data['text_today_express'] = $this->language->get('text_today_express');
		$this->data['text_today_express_saver'] = $this->language->get('text_today_express_saver');

		$this->data['entry_client_id'] = $this->language->get('entry_client_id');
		$this->data['entry_client_secret'] = $this->language->get('entry_client_secret');
		$this->data['entry_origin'] = $this->language->get('entry_origin');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_service'] = $this->language->get('entry_service');
		$this->data['entry_own_hands'] = $this->language->get('entry_own_hands');
		$this->data['entry_receive_alert'] = $this->language->get('entry_receive_alert');
		$this->data['entry_send_values'] = $this->language->get('entry_send_values');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_extra_days'] = $this->language->get('entry_extra_days');
		$this->data['entry_debug'] = $this->language->get('entry_debug');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['client_id'])) {
			$this->data['error_client_id'] = $this->error['client_id'];
		} else {
			$this->data['error_client_id'] = '';
		}

		if (isset($this->error['client_secret'])) {
			$this->data['error_client_secret'] = $this->error['client_secret'];
		} else {
			$this->data['error_client_secret'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/fretereal', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('shipping/fretereal', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['fretereal_client_id'])) {
			$this->data['fretereal_client_id'] = $this->request->post['fretereal_client_id'];
		} else {
			$this->data['fretereal_client_id'] = $this->config->get('fretereal_client_id');
		}

		if (isset($this->request->post['fretereal_client_secret'])) {
			$this->data['fretereal_client_secret'] = $this->request->post['fretereal_client_secret'];
		} else {
			$this->data['fretereal_client_secret'] = $this->config->get('fretereal_client_secret');
		}

		if (isset($this->request->post['fretereal_test'])) {
			$this->data['fretereal_test'] = $this->request->post['fretereal_test'];
		} else {
			$this->data['fretereal_test'] = $this->config->get('fretereal_test');
		}

		if (isset($this->request->post['fretereal_own_hands'])) {
			$this->data['fretereal_own_hands'] = $this->request->post['fretereal_own_hands'];
		} else {
			$this->data['fretereal_own_hands'] = $this->config->get('fretereal_own_hands');
		}

		if (isset($this->request->post['fretereal_receive_alert'])) {
			$this->data['fretereal_receive_alert'] = $this->request->post['fretereal_receive_alert'];
		} else {
			$this->data['fretereal_receive_alert'] = $this->config->get('fretereal_receive_alert');
		}

		if (isset($this->request->post['fretereal_send_values'])) {
			$this->data['fretereal_send_values'] = $this->request->post['fretereal_send_values'];
		} else {
			$this->data['fretereal_send_values'] = $this->config->get('fretereal_send_values');
		}

		if (isset($this->request->post['fretereal_status'])) {
			$this->data['fretereal_status'] = $this->request->post['fretereal_status'];
		} else {
			$this->data['fretereal_status'] = $this->config->get('fretereal_status');
		}

		if (isset($this->request->post['fretereal_sort_order'])) {
			$this->data['fretereal_sort_order'] = $this->request->post['fretereal_sort_order'];
		} else {
			$this->data['fretereal_sort_order'] = $this->config->get('fretereal_sort_order');
		}

		if (isset($this->request->post['fretereal_extra_days'])) {
			$this->data['fretereal_extra_days'] = $this->request->post['fretereal_extra_days'];
		} else {
			$this->data['fretereal_extra_days'] = $this->config->get('fretereal_extra_days');
		}

		if (isset($this->request->post['fretereal_debug'])) {
			$this->data['fretereal_debug'] = $this->request->post['fretereal_debug'];
		} else {
			$this->data['fretereal_debug'] = $this->config->get('fretereal_debug');
		}

        if ($this->data['entry_client_id'] == "" || $this->data['entry_client_secret'] == "") {
        	$this->data['fretesAceitos'] = false;
        	$_SESSION['fretereal']['fretes'] = false;
        } else {
        	$dadosParaAPI = array(
        		'client_id' => $this->data['fretereal_client_id'],
        		'client_secret' => $this->data['fretereal_client_secret']
    		);

        	$caminhoUrl = "https://fretereal.com/oauth/";
            $caminhoApi = $caminhoUrl . "action/getFretes";
            $caminhoToken = $caminhoUrl . "action/request_token";

            $curl = curl_init($caminhoToken);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'client_id' => $this->data['fretereal_client_id'],
                'client_secret' => $this->data['fretereal_client_secret'],
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

	        	$this->data['fretesAceitos'] = $return;
	        	$_SESSION['fretereal']['fretes'] = $return;
            } else {
            	$this->data['fretesAceitos'] = false;
            	$_SESSION['fretereal']['fretes'] = false;
            }
		}

		$this->template = 'shipping/fretereal.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fretereal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fretereal_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['fretereal_client_secret']) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>