<?php
    session_start();
    require_once('../Composants/header.php');
    require_once('../Composants/navbar.php');
    require_once('../Composants/footer.php');
    require_once('../Composants/Article_Comment.php');
    require_once('../Utils.php');

    // Recuperer l'article et les commentaires liés à cet article  / Implementer un controller
    
    // On récupere l'id de l'article
    $url = explode("/", filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));
    $idArticle = explode("Article.php?id=%20",$url[4])[1];
    $ErrorCheckArticle = false;
    $ErrorCheckComments = false;

    // On récupere l'article
    $reqArticle = createGetRequest("http://localhost/Blog/API/index.php/Articles/" . $idArticle);
    if ($reqArticle['Statut'] == 200){
        // On recupère les catégories 
        $reqArticle = $reqArticle['Data'][0];
        $reqCategories = createGetRequest("http://localhost/Blog/API/index.php/Articles/" . $idArticle . '/Categories');
        if($reqCategories['Statut'] === 200 ){
            $reqCategories = $reqCategories['Data'][0];
            
            // On récupere ensuite chaque nom pour chaque categorie
            $Categorie1 = createGetRequest('http://localhost/Blog/API/Index.php/Categories/' . $reqCategories[1]);
            $Categorie2 = createGetRequest('http://localhost/Blog/API/Index.php/Categories/' . $reqCategories[2]);
            $Categorie3 = createGetRequest('http://localhost/Blog/API/Index.php/Categories/' . $reqCategories[3]);

            if($Categorie1["Statut"] === 200 and $Categorie2["Statut"] === 200 and $Categorie3["Statut"] === 200){
            $Categorie1 = $Categorie1["Data"][0]["Name"];
            $Categorie2 = $Categorie2["Data"][0]["Name"];
            $Categorie3 = $Categorie3["Data"][0]["Name"];
            }

            $article = ["Id" => $reqArticle["id"],"Titre" => $reqArticle["Titre"],"Pseudo" => $reqArticle["Pseudo"],"Description" =>$reqArticle["Description"],"Categorie1" => $Categorie1,"Categorie2" => $Categorie2,"Categorie3" => $Categorie3];

        }
        else {
            $ErrorCheckArticle = true;
        }
    }
    else{
        $ErrorCheckArticle = true;
    }

    // On récupere les commentaires de l'article
    $reqComments = createGetRequest("http://localhost/Blog/API/index.php/Articles/" . $idArticle . '/Comments');
    if($reqComments['Statut'] === 200){
        $Comments = $reqComments['Data'];
        $nbComments = count($Comments);
    }
    else{
        $ErrorCheckComments = true;
        $nbComments = 0;
    }

    
    headerVue();
    display_Navbar();

    if($ErrorCheckArticle){
        echo '<h1>Pas d article dispo</h1>';
        footerVue();
        die();
    }

    // Fonction pour display une catégorie
    function displayCategorie($name,$color){
        echo '<div class="border border-black w-max h-3/6 m-5 text-center text-white font-semibold p-1 ' . $color . '" >' . $name .'</div>';
    }
?>
    <div class="flex ">
    <div class=" w-7/12 h-max ml-32 mt-5 bg-white">
        <img class="w-max h-72" src="../images/DefaultArticlePhoto.png" alt="Photo Article"/>
        <div class=" h-max">
            <div class="flex justify-between border-b border-black">
                <div class=" flex">
                    <?php
                        for($i=1;$i<=3;$i++){
                            $index = "Categorie" . $i;
                            switch($i){
                                case 1 : $color = 'bg-yellow-400';
                                    break;
                                case 2 : $color = 'bg-orange-500';
                                    break;
                                case 3 : $color = 'bg-pink-500';
                                    break;
                                default : $color='';
                                break;
                            }

                            if($article[$index] !== "Undefined"){
                            displayCategorie($article[$index],$color);
                            }
                        }
                    ?>
                </div>
                <div class="text-center m-5 font-medium"><?php echo $article["Pseudo"]?></div>
            </div>
            <div class="m-4 flex flex-col gap-5">
                <?php echo '<h1 class="font-semibold text-center">' . $article["Titre"]. '</h1>';?>
                <?php echo $article["Description"]?>
                </div>
        </div>
    </div>
    <div class="ml-16 w-3/12 mt-5">
        <div class="flex">
            <h1 class="font-bold m-3">Commentaires</h1>
            <?php echo '<div class="font-bold m-3">' . $nbComments . '</div>' ?>
        </div>
        <div class="flex flex-wrap">
            <?php 
                if($ErrorCheckComments){
                    echo '<h1> Pas de commentaires à cet article</h1>';
                }
                else{
                for($i=0;$i<$nbComments;$i++){
                    display_Comment($Comments[$i]);
                }
            }
            ?>
        </div>
    
        <div class="border border-black">
            <form name="form" class="flex flex-col" action="../Controller.php" method="post">
                <input name='Request' value="PostComment" hidden/>
                <input name='IdArticle' value=<?php echo $article["Id"]?> hidden/>
                <textarea name='Desc' placeholder="Postez votre commentaire"></textarea>
                <button class="border border-black rounded-md p-1" type="Submit"> Envoyez</button>
            </form>
        </div>
    </div>
    </div>      
<?php
    footerVue();
?>