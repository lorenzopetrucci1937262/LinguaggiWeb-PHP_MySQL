<?php

require_once("dati_generali.php");

$pagina_corrente = basename($_SERVER['PHP_SELF']);
?>

<style type="text/css">
   .link-disattivato {
      pointer-events: none; 
      cursor: default;
      text-decoration: none;
   }

   .scroll-top-btn {
      position: fixed;
      bottom: 25px;
      right: 25px;
      width: 42px;
      height: 42px;
      background-color: #092064;
      color: #d8d8d89f;
      text-align: center;
      text-decoration: none;
      line-height: 32px;
      font-size: 46px;
      z-index: 9999; 
      transition: all 0.3s ease;
   }
   .scroll-top-btn:hover {
      background-color: #00072e;
      color: #faf3e0;
      text-decoration: none;
   }
</style>

<div class="header">
   <a class="header <?php if ($pagina_corrente == 'programmazione.php') echo 'link-disattivato'; ?>" href="programmazione.php">FILM IN CALENDARIO</a>
   <h1>
      <a class="h1 <?php if ($pagina_corrente == 'index.php') echo 'link-disattivato'; ?>" href="index.php"><?php echo $cinema_nome; ?></a>
   </h1>
   <a class="header <?php if ($pagina_corrente == 'presentazione.php') echo 'link-disattivato'; ?>" href="presentazione.php">IL NOSTRO CINEMA</a>
</div>

<a href="#" class="scroll-top-btn" title="Torna in alto">&#x2227;</a>