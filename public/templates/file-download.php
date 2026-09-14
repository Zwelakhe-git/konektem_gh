<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <meta name="theme-color" content="black"/>
        <meta http-equiv="Cache-Control" content="max-age=31536000">
        <title><?= $title ?? 'Konektem'?></title>
        <link rel="shortcut icon" type="image/png" href="/media/images/favicon.png"/>
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://kit.fontawesome.com/6f0be4257f.js" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" nomodule></script>
        
        <?php if(isset($styles)){
            foreach($styles as $url){?>
            <link rel="stylesheet" href="<?= $url?>" />
        <?php } }
        if(isset($scripts)){
            foreach($scripts as $script){?>
            <script src="<?= $script['url']?>"
            <?php if(isset($script['params'])){
                    foreach($script['params'] as $param => $value){?>
                    <?= $param . "=\"$value\""?>
            <?php } }?> ></script><?php }?>
        <?php }?>
    </head>
    <body>
        <div class="section w-full m-auto flex col">
            <div class="section-info">
                <h1>Download File</h1>
            </div>
            <div class="flex box col center">
                <div class="file-name">
                    <h2><?= $file_name?></h2>
                </div>
                <button type="button" class="btn btn-primary download-btn bold">Download</button>
            </div>
        </div>
        <script>
            const downloadFile = () => {
                try{
                    console.log('downloading file');
                    let a = document.createElement("a");
                    a.download = "<?= $file_name?>";
                    a.href = "<?= $file_url?>";
                    a.click();
                } catch(err){
                    console.log(err);
                }
            };
            let downloadButton = document.querySelector('.download-btn');
            downloadButton.addEventListener('click', () => {
                console.log("button clicked");
                downloadFile();
                downloadButton.disabled = true;
            })
        </script>
    </body>
</html>