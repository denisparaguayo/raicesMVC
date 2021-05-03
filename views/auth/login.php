<main class="contenedor seccion contenido-centrado">
        <h1>Inicia Sesión</h1>

        <?php foreach($errores as $error) : ?>
        <div class="alerta error">
        <?php echo $error?>
        </div>

        <?php endforeach;?>

        <form method="POST" class="formulario" action="/login">
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email" >E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" autocomplete="off" >

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password" autocomplete="off">

            </fieldset>
            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>



    </main>