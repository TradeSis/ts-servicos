<?php
//Gabriel 28092023 ID 575 Demandas/Comentarios - Layout de chat
include_once '../header.php';
include_once '../database/mensagem.php';
$HOJE = strtotime(date("Y-m-d"));

if (isset($_POST['fetch_msg'])) {
    if ($_POST['buscar'] == "mensagem") {
        $idUsuario = $_POST['idUsuario'];
        $idDemanda = $_POST['idDemanda'];
        $mensagens = buscaMensagem($idDemanda, $_SESSION['idUsuario']);

        foreach ($mensagens as $mensagem) {
            // helio 041023 - teste de datas
            $time2 = strtotime($mensagem['dataMensagem']);
            $difference = floor(($time2 - $HOJE) / (60 * 60 * 24)) + 1;
            $dataHora = date('d/m/Y H:i', strtotime($mensagem['dataMensagem']));

            if ($difference == 0) {
                $dataHora = "hoje " . date('H:i ', strtotime($mensagem['dataMensagem']));
            } else if ($difference == -1) {
                $dataHora = "ontem " . date('H:i ', strtotime($mensagem['dataMensagem']));
            }

            ?>
            <div id="chat" class="<?php echo $mensagem['status'] == 0 ? 'msg outgoing' : 'msg incoming' ?>">
                <div class="details">

                    <?php if ($mensagem['pathAnexo'] != '') { ?>
                        <p> <!-- COLOCA TUDO DENTRO DO PARAGRAFO -->
                            <strong>
                                <i class="bi bi-person-circle"></i>
                                <?php echo $mensagem['nomeUsuario'] ?>
                            </strong><em>
                                <?php echo $dataHora ?>
                            </em>

                            <BR>
                            <?php echo nl2br($mensagem['mensagem']) ?>

                            <a target="_blank" href="<?php echo $mensagem['pathAnexo'] ?>">
                                <?php echo $mensagem['nomeAnexo'] ?>
                            </a>

                        </p>

                    <?php } else { ?>
                        <p> <!-- COLOCA TUDO DENTRO DO PARAGRAFO -->
                            <strong>
                                <i class="bi bi-person-circle"></i>
                                <?php echo $mensagem['nomeUsuario'] ?>
                            </strong><em>
                                <?php echo $dataHora ?>
                            </em>

                            <BR>
                            <?php echo nl2br($mensagem['mensagem']) ?>

                        </p>
                        <a target="_blank" href="<?php echo $mensagem['pathAnexo'] ?>">
                            <?php echo $mensagem['nomeAnexo'] ?>
                        </a>

                    <?php } ?>
                </div>
            </div>
        <?php }
    }
    if ($_POST['buscar'] == "chat") {
        if ($_POST['OUTidUsuario'] !== "") {
            $chats = buscaChat($_SESSION['idUsuario'], $_POST['OUTidUsuario']);
        } else {
            $chats = buscaChat($_SESSION['idUsuario']);
        }
        foreach ($chats as $chat) {
            if (
                $_POST['chat_geral'] == "false" && $chat['OUTidUsuario'] !== null &&
                ($_SESSION['idUsuario'] == $chat['INidUsuario'] || $_SESSION['idUsuario'] == $chat['OUTidUsuario'])
            ) { ?>
                <div id="chat" class="<?php echo $chat['status'] == 0 ? 'msg outgoing' : 'msg incoming' ?>">
                    <div class="details">
                        <div class="user-img"><i class="bi bi-person-circle" style="font-size: 15px;color: black;"></i>
                            <?php echo $chat['nomeINusuario'] ?> -
                            <?php echo date('H:i d/m/Y', strtotime($chat['dataMensagem'])) ?>
                        </div>
                        <p> 
                            <!-- Gabriel 05102023 ID 575 formatação chat -->
                            <?php echo nl2br($chat['chat']) ?>
                        </p>
                    </div>
                </div>
                <?php
            }
            if ($_POST['chat_geral'] == "true" && $chat['OUTidUsuario'] == null) {
                ?>
                <div id="chat" class="<?php echo $chat['status'] == 0 ? 'msg outgoing' : 'msg incoming' ?>">
                    <div class="details">
                        <div class="user-img"><i class="bi bi-person-circle" style="font-size: 15px;color: black;"></i>
                            <?php echo $chat['nomeINusuario'] ?> -
                            <?php echo date('H:i d/m/Y', strtotime($chat['dataMensagem'])) ?>
                        </div>
                        <p>
                            <!-- Gabriel 05102023 ID 575 formatação chat -->
                            <?php echo nl2br($chat['chat']) ?>
                        </p>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>