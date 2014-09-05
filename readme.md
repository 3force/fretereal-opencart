#Frete Real - Plugin para OpenCart - v.0.0.1
Este módulo contempla a integração completa do Frete Real para lojas que utilizam a plataforma OpenCart.

##Funcionalidades
* Busca em sua conta do Frete Real os tipos de frete que sua empresa trabalha
* Integrado para declarar valores (caso sua empresa utilize fretes aéreos, com seguro, etc..)
* Integrado para solicitar a cotação de Aviso de Recebimento e Mão Própria
* Exibe informações recebidas pelos Correios (ex: Áreas de Risco)
* Integrado para calcular, em apenas 1 request, todos os tipos de fretes disponíveis
* Integrado para trabalhar com as diferentes categorias de pesos dos produtos (Kg, G, Oz, Lb)

##Instalação
Para instalar este módulo, cadastre-se no [Frete Real](https://fretereal.com) para obter as credenciais de acesso para utilizar a API. Em seguida efetue o seguinte procedimento:

1. Baixe este arquivo .zip
2. Extraia o .zip na pasta raiz do seu OpenCart
3. Vá até o painel administrativo e acesse "Extensions -> Shipping" e instale o Frete Real
4. Na configuração, informe as suas credenciais (client_id e client_secret) e salve as configurações para o sistema carregar os fretes aceitos pela sua empresa
5. Selecione os fretes que deseja utilizar na loja
6. Configure os demais dados
7. Edite o arquivo  "ROOT/catalog/model/checkout/order.php" e adicione em cima da linha "return $order_id;" do método "addOrder" o seguinte bloco de informaçõe

```php
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
```

##Entendendo como funciona
Quando um cliente adiciona um produto no carrinho, e informa seu "POSTCODE" (CEP), o sistema verifica as configurações e envia para a API as informações do carrinho.

Quando o Frete Real retorna as informações, armazenamos na $_SESSION uma identificação para este frete, e também os valores; assim você não consome cálculos repetidos enquanto o cliente não efetua a compra.

Os dados dos cálculos, como por ex: Caixas utilizadas, os produtos dentro de cada caixa ficam armazenados nos "Comentários da compra"