<?php
session_start();
require_once(dirname(dirname(__FILE__))."/config/abrealas.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <script>
           new DataTable('#idTabela', {
//                columnDefs: [
//                    {
//                        target: 5,
//                        searchable: false,
//                        orderable: false
//                    },
//                    {
//                        target: 6,
//                        searchable: false,
//                        orderable: false
//                    },
//                    {
//                        target: 7,
//                        searchable: false,
//                        orderable: false
//                    },
//                    {
//                        target: 8,
//                        searchable: false,
//                        orderable: false
//                    },
//                    {
//                        target: 9,
//                        searchable: false,
//                        orderable: false
//                    },
//                    {
//                        target: 10,
//                        searchable: false,
//                        orderable: false
//                    }
//                ],
                lengthMenu: [
                    [50, 100, 200, 500],
                    [50, 100, 200, 500]
                ],
                language: {
                    info: 'Mostrando Página _PAGE_ of _PAGES_',
                    infoEmpty: 'Nenhum registro encontrado',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    lengthMenu: 'Mostrando _MENU_ registros por página',
                    zeroRecords: 'Nada foi encontrado'
                }
            });
            table = new DataTable('#idTabela');

        </script>
    </head>
    <body> 
        <?php
            $Cod = (int) filter_input(INPUT_GET, 'codigo');
            $rs = pg_query($Conec, "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'bensachados'");
            $row = pg_num_rows($rs);
            if($row == 0){
                die("Faltam tabelas. Informe à ATI");
                return false;
            }
        ?>
         <!-- Apresenta os usuários do setor com o nível administrativo -->
        <div style="padding: 10px;">
            <?php
            $rs0 = pg_query($Conec, "SELECT ".$xProj.".bensachados.id, to_char(".$xProj.".bensachados.datareceb, 'DD/MM/YYYY'), numprocesso, descdobem, usuguarda, usurestit, usucsg, usuarquivou, TO_CHAR(AGE(CURRENT_DATE, datareceb), 'MM') AS intervalo 
            FROM ".$xProj.".bensachados INNER JOIN ".$xProj.".poslog ON ".$xProj.".bensachados.codusuins = ".$xProj.".poslog.pessoas_id
            WHERE ".$xProj.".bensachados.ativo = 1 And AGE(".$xProj.".bensachados.datareceb, CURRENT_DATE) <= '1 YEAR' 
            ORDER BY ".$xProj.".bensachados.datareceb DESC");

            $Edit = 0;
            $Impr = 0;
            $admIns = parAdm("insbens", $Conec, $xProj);   // nível para inserir 
            $admEdit = parAdm("editbens", $Conec, $xProj); // nível administrativo para editar
            $Marca = parEsc("bens", $Conec, $xProj, $_SESSION["usuarioID"]); // ver se está marcado no cadastro de usu
            if($Marca == 1 && $_SESSION["AdmUsu"] >= $admIns || $_SESSION["AdmUsu"] > 6){
                $Edit = 1;
                if($_SESSION["AdmUsu"] >= $admEdit || $_SESSION["AdmUsu"] > 6){
                    $Impr = 1;
                }
            }
            ?>
            <table id="idTabela" class="display" style="width:85%">
                <thead>
                    <tr>
                        <th style="display: none;"></th>
                        <th style="display: none;"></th>
                        <th>Data</th>
                        <th style="text-align: center;">Nº Processo</th>
                        <th style="text-align: center;">Descrição do Bem</th>
                        <?php
                        if($Edit == 1){
                        ?>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        <?php
                        }
                        ?>
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($tbl0 = pg_fetch_row($rs0)){
                        $SobGuarda = $tbl0[4];
                        $Restit = $tbl0[5];
                        $GuardaCSG = $tbl0[6];
                        $Arquivado = $tbl0[7];
                        $Intervalo = (int) $tbl0[8];
                        ?>
                        <tr>
                            <td style="display: none;"></td>
                            <td style="display: none;"><?php echo $tbl0[0]; ?></td>
                            <td><?php echo $tbl0[1]; ?></td> <!-- data -->
                            <td style="text-align: center;"><?php echo $tbl0[2]; ?></td> <!-- num processo -->
                            <td><?php echo nl2br($tbl0[3]); ?></td> <!-- descrição do bem -->
                            <?php
                            if($Edit == 1){
                            ?>

                            <td title="Visualizar o registro de recebimento">
                                <?php 
                                if($Edit == 1 && $SobGuarda == 0 && $Restit == 0 && $Arquivado == 0){
                                    echo "<button class='botTable fundoAmarelo' onclick='verRegistroRcb($tbl0[0]);'>Editar</button>";
                                }else{
                                    echo "<button disabled class='botTable fundoCinza corAzulClaro'>Editar</button>";
                                }
                                ?>
                            </td>
                            <td title="Transferir para a guarda da DAF por 90 dias">
                                <?php 
                                if($Edit == 1 && $SobGuarda == 0 && $Restit == 0 && $Arquivado == 0){
                                    echo "<button class='botTable fundoAmarelo' onclick='mostraBem($tbl0[0], 1, $Restit);'>Guarda</button>";
                                } else{
                                    echo "<button disabled class='botTable fundoCinza corAzulClaro'>Guarda</button>";
                                }
                                ?>
                            </td>
                            <td title="Registro de restituição do bem">
                                <?php 
                                    echo "<button class='botTable fundoAmarelo' onclick='mostraBem($tbl0[0], 2, $Restit);'>Restituição</button>";
//                                if($Edit == 1 && $Restit == 0 && $GuardaCSG == 0 && $Arquivado == 0){
//                                        echo "<button class='botTable fundoAmarelo' onclick='mostraBem($tbl0[0], 2, $Restit);'>Restituição</button>";
//                                    }else{
//                                        echo "<button disabled class='botTable fundoCinza corAzulClaro'>Restituição</button>";
//                                    }
                                ?>
                            </td>
                            <td title="Encaminhamento para CSG após 90 dias">
                                <?php 
                                if($Edit == 1 && $Restit == 0 && $GuardaCSG == 0 && $Arquivado == 0 && $Intervalo > 2){
                                    echo "<button class='botTable fundoAmarelo' onclick='mostraBem($tbl0[0], 3, $Restit);'>CSG</button>";
                                }else{
                                    echo "<button disabled class='botTable fundoCinza corAzulClaro'>CSG</button>";
                                }
                                ?>
                            </td>
                            <td title="Destinação após 90 dias">
                                <?php 
                                if($Edit == 1 && $Arquivado == 0 && $Intervalo > 2){
                                    echo "<button class='botTable fundoAmarelo' onclick='mostraBem($tbl0[0], 4, $Restit);'>Destinação</button>";
                                }else{
                                    echo "<button disabled class='botTable fundoCinza corAzulClaro'>Destinação</button>";
                                }
                                ?>
                            </td>
                            <td title="Gerar PDF do processo">
                                <?php 
                                if($Impr == 1){ // nível adm para editar
                                    echo "<button class='botTable fundoAmarelo' onclick='imprProcesso($tbl0[0]);'>PDF</button>";
                                }
                                ?>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>