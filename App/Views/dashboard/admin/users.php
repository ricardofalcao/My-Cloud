<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">

    <title>My Cloud</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/app.css">
</head>

<body>
    <div id="app">
        <nav class="navbar">
            <div class="navbar-title">
                <i class="fas fa-cloud"></i>
                <span class="navbar-title-text">My Cloud</span>
            </div>

            <ul class="navbar-links">
                <li class="navbar-link">
                    <a href="../">Início</a>
                </li>
                <li class="navbar-link">
                    <a href="../login">Login</a>
                </li>
                <li class="navbar-link">
                    <a href="../settings">Definições</a>
                </li>
                <li class="navbar-avatar">
                    <img src="../images/avatar_ricardofalcao.jpg"/>

                    <div class="arrow"></div>
                    <ul class="dropdown">
                        <li class="dropdown-title">Ricardo Falcão</li>
                        <li class="dropdown-link">
                            <a href="#">
                                <i class="fas fa-cogs"></i>
                                Definições
                            </a>
                        </li>
                        <li class="dropdown-link">
                            <a href="#">
                                <i class="fas fa-sign-out-alt"></i>
                                Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <main>
            <nav class="sidebar">
                <ul class="sidebar-top">
                    <li class="sidebar-title">
                        Definições
                    </li>
                    <li class="sidebar-link">
                        <a href="../settings">
                            <span class="sidebar-label">
                                <i class="fas fa-tasks"></i>
                                <span class="sidebar-label-text">Geral</span>
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-link active">
                        <a href="../settings/users.html">
                            <span class="sidebar-label">
                                <i class="fas fa-users"></i>
                                <span class="sidebar-label-text">Utilizadores</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="content">
                <table class="datatable" cellspacing="0" rowspacing="0">
                    <thead class="datatable-header">
                        <tr>
                            <th class="user-icon"></th>
                            <th class="user-username">Username</th>
                            <th class="user-name">Nome</th>
                            <th class="user-actions">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="datatable-body">
                        <tr class="datatable-item">
                            <td class="user-icon">
                                <img src="../images/avatar_ricardofalcao.jpg"/>
                            </td>
                            <td class="user-username">ricardofalcao</td>
                            <td class="user-name">Ricardo Falcão</td>
                            <td class="user-actions">
                                <i class="fas fa-trash"></i>
                            </td>
                        </tr>
                        <tr class="datatable-item">
                            <td class="user-icon">
                                <img src="../images/avatar_cristianoronaldo.jpg"/>
                            </td>
                            <td class="user-username">cristianoronaldo</td>
                            <td class="user-name">Cristiano Ronaldo</td>
                            <td class="user-actions">
                                <i class="fas fa-trash"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="container">
                    <form action="#" class="form users-form">
                        <h1 class="form-title">Adicionar utilizador</h1>

                        <div class="users-inputs">
                            <div class="users-input">
                                <label for="username">Username</label>
                                <div class="form-input-text">
                                    <i class="fas fa-user"></i>
                                    <input name="username" type="text" placeholder="joaoexemplo" />
                                </div>
                            </div>

                            <div class="users-input">
                                <label for="username">Nome</label>
                                <div class="form-input-text">
                                    <i class="fas fa-address-book"></i>
                                    <input name="name" type="text" placeholder="João Exemplo" />
                                </div>
                            </div>
            
                            <div class="users-input">
                                <label for="password">Password</label>
                                <div class="form-input-text">
                                    <i class="fas fa-key"></i>
                                    <input name="password" type="password" placeholder="Password" />
                                </div>
                            </div>
            
                            <div class="users-input">
                                <label for="password">Confirmar Password</label>
                                <div class="form-input-text">
                                    <i class="fas fa-key"></i>
                                    <input name="password" type="password" placeholder="Password" />
                                </div>
                            </div>
                        </div>
        
                        <button class="form-submit" type="submit">Submeter</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
