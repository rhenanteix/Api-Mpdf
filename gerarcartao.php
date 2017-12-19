<?php  




 // Require no script da classe reportCliente  
 require_once "reportCliente.class.php"; 
 
 /*  
  * Verifica se é uma submissão, se for instância o objeto reportCliente  
  * passa os parâmetros para o construtor, chama o método para construção do PDF  
  * e manda exibi-lo no navegador.  
  */  
 if(isset($_GET['submit'])):  
   $report = new reportCliente("lib/css/estilo.css", "Relatório de Contas");  
   $report->BuildPDF();  
   $report->Exibir("Relatório de Cartão");  
 endif;  
 ?>  

 <!DOCTYPE html>  
 <html>  
   <head>  
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
     <title>Testando relatório com mPDF</title>  
   </head>  
   <body>  
     <form action="" method="GET" target="_blank">  
       <input type="submit" value="Gerar relatório" name="submit"/>  
     </form>  
   </body>  
 </html>  