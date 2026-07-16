<?php
declare(strict_types=1);

if(session_status()===PHP_SESSION_NONE){session_start();}

if(empty($_SESSION['admin_id'])){
    header('Location: admin/login');
    exit;
}

require_once 'db.php';

function temizle(?string $value):string{
    return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');
}

$page=$_GET['page']??'select';

if($page!=='select'){
    $page=trim((string)$page);
    if($page===''||mb_strlen($page,'UTF-8')>150||!preg_match('/^[a-zA-Z0-9_\-\/\.]+$/',$page)){
        $page='select';
    }
}

$clicks=[];
$allPages=[];

try{
    if($page!=='select'){
        $stmt=$pdo->prepare("SELECT click_x,click_y FROM clicks WHERE page_url=:page ORDER BY id DESC LIMIT 5000");
        $stmt->execute([':page'=>$page]);
        $clicks=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $pagesStmt=$pdo->query("SELECT DISTINCT page_url FROM clicks WHERE page_url IS NOT NULL AND page_url!='' ORDER BY page_url ASC");
    $allPages=$pagesStmt->fetchAll(PDO::FETCH_COLUMN);
}catch(PDOException $e){
    error_log('Heatmap hatası: '.$e->getMessage());
}

$clicksJson=json_encode($clicks,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Isı Haritası Admin</title>
<meta name="robots" content="noindex,nofollow">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<script src="https://www.nfcmedya.com.tr/assets/js/heatmap.min.js"></script>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<style>
html,body{margin:0;padding:0;height:100%;font-family:Arial,sans-serif;background:#f8fafc}
#pageContainer{position:relative;width:100%;height:100vh;margin-top:0}
#pageFrame{width:100%;height:100%;border:0;display:block;position:relative;z-index:1}
#parentHeatmapOverlay{position:fixed;top:64px;left:0;width:100%;height:calc(100vh - 64px);pointer-events:none;z-index:999999}
</style>
</head>
<body>

<div class="fixed top-0 left-0 w-full h-16 bg-white border-b border-gray-200 shadow-lg z-50 flex items-center justify-between px-6">
    <div class="flex items-center gap-3">
        <strong class="text-[#1f2d55]">Isı Haritası</strong>

        <select id="pageSelect" class="bg-gray-50 border border-gray-300 rounded-lg py-3 px-6 shadow-md text-base font-medium focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="select" <?= $page==='select'?'selected':''; ?>>Lütfen sayfa seçiniz</option>

            <?php foreach($allPages as $p): ?>
                <?php
                $safePage=trim((string)$p);
                if($safePage===''||mb_strlen($safePage,'UTF-8')>150||!preg_match('/^[a-zA-Z0-9_\-\/\.]+$/',$safePage)){
                    continue;
                }
                ?>
                <option value="<?= temizle($safePage); ?>" <?= $safePage===$page?'selected':''; ?>>
                    <?= temizle($safePage); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <a href="admin/index" class="bg-[#1f2d55] hover:bg-[#162040] text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-colors text-base">
        Admine Geri Dön
    </a>
</div>

<div id="pageContainer" class="pt-16">
    <?php if($page!=='select'): ?>
        <iframe id="pageFrame" src="<?= temizle($page); ?>" class="w-full h-[calc(100vh-4rem)] border-0"></iframe>
        <div id="parentHeatmapOverlay"></div>
    <?php else: ?>
        <div class="flex flex-col justify-center items-center h-full text-gray-500 text-lg">
            <div class="bg-white rounded-2xl shadow-md p-10 text-center">
                <h1 class="text-2xl font-bold text-[#1f2d55] mb-2">Sayfa Seçiniz</h1>
                <p>Isı haritasını görmek için üst menüden bir sayfa seçin.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded",function(){
    const select=document.getElementById("pageSelect");

    if(select){
        select.addEventListener("change",function(){
            const selected=this.value;

            if(selected&&selected!=="select"){
                window.location.href="heatmap_pages?page="+encodeURIComponent(selected);
            }
        });
    }
});
</script>

<?php if($page!=='select'): ?>
<script>
(function(){
    const clicksRaw=<?= $clicksJson ?: '[]'; ?>;

    const heatData=clicksRaw.map(c=>({
        x:Math.round(Number(c.click_x)||0),
        y:Math.round(Number(c.click_y)||0),
        value:1
    }));

    const iframe=document.getElementById("pageFrame");
    const parentOverlay=document.getElementById("parentHeatmapOverlay");

    function initParentFallback(){
        parentOverlay.style.display="block";

        if(typeof h337==="undefined"){
            return;
        }

        const heatmap=h337.create({
            container:parentOverlay,
            radius:25,
            maxOpacity:.7,
            minOpacity:0,
            blur:.85
        });

        function updateParentSize(){
            const w=iframe.offsetWidth;
            const h=iframe.offsetHeight;

            parentOverlay.style.width=w+"px";
            parentOverlay.style.height=h+"px";

            if(heatmap&&heatmap._renderer){
                heatmap._renderer.setDimensions(w,h);
            }
        }

        updateParentSize();
        window.addEventListener("resize",updateParentSize);

        heatmap.setData({
            max:10,
            data:heatData
        });
    }

    iframe.addEventListener("load",function(){
        try{
            const idoc=iframe.contentDocument||iframe.contentWindow.document;
            const iwin=iframe.contentWindow;

            const styleEl=idoc.createElement("style");
            styleEl.textContent=`
                html,body{height:100%;margin:0;padding:0}
                body{position:relative!important}
                #heatmapOverlayIframe{
                    position:absolute!important;
                    top:0;
                    left:0;
                    width:100%;
                    height:100%;
                    pointer-events:none!important;
                    z-index:2147483646!important;
                }
            `;

            if(!idoc.head){
                const head=idoc.createElement("head");
                idoc.documentElement.insertBefore(head,idoc.body);
            }

            idoc.head.appendChild(styleEl);

            let overlay=idoc.getElementById("heatmapOverlayIframe");

            if(!overlay){
                overlay=idoc.createElement("div");
                overlay.id="heatmapOverlayIframe";
                idoc.body.appendChild(overlay);
            }

            function startHeatmapInside(){
                try{
                    if(typeof iwin.h337==="undefined"){
                        initParentFallback();
                        return;
                    }

                    const heatmap=iwin.h337.create({
                        container:overlay,
                        radius:25,
                        maxOpacity:.9,
                        blur:.75
                    });

                    function updateSize(){
                        const w=Math.max(idoc.body.scrollWidth,idoc.documentElement.clientWidth);
                        const h=Math.max(idoc.body.scrollHeight,idoc.documentElement.clientHeight);

                        overlay.style.width=w+"px";
                        overlay.style.height=h+"px";

                        if(heatmap&&heatmap._renderer){
                            heatmap._renderer.setDimensions(w,h);
                        }
                    }

                    updateSize();

                    heatmap.setData({
                        max:10,
                        data:heatData
                    });

                    iwin.addEventListener("resize",updateSize);
                }catch(e){
                    initParentFallback();
                }
            }

            if(typeof iwin.h337!=="undefined"){
                startHeatmapInside();
            }else{
                const script=idoc.createElement("script");
                script.src="https://www.nfcmedya.com.tr/assets/js/heatmap.min.js";
                script.onload=startHeatmapInside;
                script.onerror=initParentFallback;
                idoc.body.appendChild(script);
            }
        }catch(err){
            initParentFallback();
        }
    });
})();
</script>
<?php endif; ?>

</body>
</html>