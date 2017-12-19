<?php  


session_start();
if($_SESSION['logado'] == false){
  header("location: ../View/TelaLogin.php");
}
//session_start();
//$UserId=$_SESSION['idUsuario'];

// Teste
 require_once "../Model/Pdf.php";  
 require_once "../MPDF/mpdf.php"; 




  class reportCliente extends mpdf{  

    // Atributos da classe  
    private $pdo  = null;  
    private $pdf  = null;
    private $css  = null;  
    private $titulo = null; 
 
    /*  
    * Construtor da classe  
    * @param $css  - Arquivo CSS  
    * @param $titulo - Título do relatório   
    */  
    public function __construct($css, $titulo) {  
      $this->pdo  = Conexao::getInstance();  
      $this->titulo = $titulo;  
      $this->setarCSS($css);  
    }
  
    /*  
    * Método para setar o conteúdo do arquivo CSS para o atributo css  
    * @param $file - Caminho para arquivo CSS  
    */  
    public function setarCSS($file){  
     if (file_exists($file)):  
       $this->css = file_get_contents($file);  
     else:  
       echo 'Arquivo inexistente!';  
     endif;  
    }  

    /*  
    * Método para montar o Cabeçalho do relatório em PDF  
    */  
    protected function getHeader(){  
       $data = date('j/m/Y');  
       $retorno = "<table class=\"tbl_header\" width=\"1000\">  
               <tr>  
                 <td align=\"left\">ewallet contas </td>  
                 <td align=\"right\">Gerado em: $data</td>  
               </tr>  
             </table>";  
       return $retorno;  
     }  

     /*  
     * Método para montar o Rodapé do relatório em PDF  
     */  
     protected function getFooter(){  
       $retorno = "<table class=\"tbl_footer\" width=\"1000\">  
               <tr>  
                 <td align=\"left\"><a href=''>ewallet.com.br</a></td>  
                 <td align=\"center\">Total: </td>
                 <td align=\"right\">Página: {PAGENO}</td>  
               </tr>  
             </table>";  
       return $retorno;  
     }  

    /*   
    * Método para construir a tabela em HTML com todos os dados  
    * Esse método também gera o conteúdo para o arquivo PDF  
    */  
    private function getTabela(){  
      $color  = false;  
      $retorno = ""; 
      

      $retorno .= "<h2 style=\"text-align:center\">{$this->titulo}</h2>";  
      $retorno .= "<table border='1' width='1000' align='center'>  
           <tr class='header'>  
             <th>Valor</td>  
             <th>Data Pagamento</td>  
             <th>Data Lançamento</td>
             
               
           </tr>";  



      $id =  $_SESSION['idUsuario'];

      $sql = "SELECT lancamentoComum.id 'idLancamento', 
                                  conta.descricao, 
                                  conta.idUsuario, 
                                  operacao.nome, 
                                  lancamentoComum.valor, 
                                  lancamentoComum.dataPagamento,
                                  lancamentoComum.dataLancamento  
                              FROM zero_ewallet.lancamentoComum 
                              left join operacao on lancamentoComum.idOperacao = operacao.id
                              left join conta on lancamentoComum.idContaComum = conta.id
                              where idUsuario = $id";  
      foreach ($this->pdo->query($sql) as $reg):  
         $retorno .= ($color) ? "<tr>" : "<tr class=\"zebra\">";  
         $retorno .= "<td class='destaque'>{$reg['valor']}</td>";  
         $retorno .= "<td>{$reg['dataPagamento']}</td>";  
         $retorno .= "<td>{$reg['dataLancamento']}</td>"; 
        // $Sum += "<td>{$reg['saldoInicial']}</td>" 
         //$retorno .= "<td>{$reg['limite']}</td>";  
         //$retorno .= "<td>{$reg['email']}</td>";  
         //$retorno .= "<td>{$reg['endereco']}</td>";  
         //$retorno .= "<td>{$reg['cidade']}</td>";  
         //$retorno .= "<td>{$reg['uf']}</td>";  
       $retorno .= "<tr>"; 
       $color = !$color;  
      endforeach;  

      $retorno .= "</table>";  
      return $retorno;  
    } 

    /*   
    * Método para construir o arquivo PDF  
    */  
    public function BuildPDF(){  
     $this->pdf = new mPDF('utf-8', 'A4-L');  
     $this->pdf->WriteHTML($this->css, 1);  
     $this->pdf->SetHTMLHeader($this->getHeader());  
     $this->pdf->SetHTMLFooter($this->getFooter());  
     $this->pdf->WriteHTML($this->getTabela());   
    }   

    /*   
    * Método para exibir o arquivo PDF  
    * @param $name - Nome do arquivo se necessário grava-lo  
    */  
    public function Exibir($name = null) {  
     $this->pdf->Output($name, 'I');  
    }  
  }   