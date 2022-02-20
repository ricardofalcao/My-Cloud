<!DOCTYPE html>
<html lang="en">

<?php

use Core\Asset;
use Core\View;

View::render('components/head.php');

$users = [
    [
        "name" => "Ricardo Falcão",
        "email" => "up201704220@edu.fe.up.pt",
        "username" => "ricardofalcao",
        "password" => "password1",
        "avatar" => "assets/images/201704220.jpg"
    ],
    [
        "name" => "Daniel Barros",
        "email" => "up201704271@edu.fe.up.pt",
        "username" => "danielbarros",
        "password" => "password2",
        "avatar" => "assets/images/201704271.jpg"
    ]
]

?>

<body>


<section class="hero is-fullheight"
         style="background-image: url('<?php Asset::get('/assets/images/login_bg.png') ?>'); background-size: cover; background-position: center;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-7-tablet is-6-desktop is-4-widescreen">
                    <div class="box">
                        <div class="mb-3">
                            <div class="level">
                                <div class="level-left">
                                    <div class="level-item">
                                        <a href="auth/login">
                                        <span class="icon-text">
                                          <span class="icon">
                                            <i class="fas fa-arrow-left"></i>
                                          </span>
                                          <span>Voltar ao login</span>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="level-right">
                                    <div class="level-item">
                                        <h1 class="title is-spaced">My Cloud</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="subtitle mb-2">Autores</h2>

                        <?php foreach($users as $user) { ?>
                            <article class="media mb-4" style="border-top: 0;">
                                <figure class="media-left">
                                    <p class="image is-96x96 image is-3by4">
                                        <img style="border-radius: 10px;" src="<?php echo $user['avatar'] ?>">
                                    </p>
                                </figure>
                                <div class="media-content is-align-self-stretch is-flex is-flex-direction-column">
                                    <div class="content">
                                        <strong><?php echo $user['name']?></strong> <br/>
                                        <small><?php echo $user['email']?></small>
                                    </div>
                                    <nav class="level is-mobile mt-auto">
                                        <div class="level-left">
                                            <div class="level-item icon-text">
                                            <span class="icon is-small">
                                                <i class="fas fa-user"></i>
                                            </span>
                                                <span><?php echo $user['username']?></span>
                                            </div>
                                            <div class="level-item icon-text">
                                            <span class="icon is-small">
                                                <i class="fas fa-key"></i>
                                            </span>
                                                <span><?php echo $user['password']?></span>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </article>
                        <?php } ?>

                        <h2 class="subtitle mb-2">Links</h2>

                        <div class="buttons">
                            <a href="docs/download" class="button">
                                <span class="icon">
                                  <i class="fas fa-file-code"></i>
                                </span>
                                <span>Código fonte</span>
                            </a>
                            <a href="https://docs.google.com/presentation/d/1CIg52DRvA99alxIvCd8xk0Gka2_FWPhIBGm682MrL7Y/edit?usp=sharing" class="button">
                                <span class="icon">
                                  <i class="fas fa-file-powerpoint"></i>
                                </span>
                                <span>Relatório final</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


</body>

</html>