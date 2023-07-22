COMANDOS wkhtmltoimage
//download página
wkhtmltoimage http://www.google.com google.jpg

//renderiza sem imagens
wkhtmltoimage --no-images http://www.google.com google.jpg

//não carrega os javascripts
wkhtmltoimage --no-javascript http://www.google.com google.jpg

//reduz a qualidade da imagem de output
wkhtmltoimage --quality 50 http://www.google.com google.jpg

//personaliza a largura e a altura do screenshot
wkhtmltoimage --height 600 --width 1800 http://www.google.com google.jpg

//faz um crop de 300x300 pixels partindo do eixo x0 e y0
wkhtmltoimage --crop-h 300 --crop-w 300 --crop-x 0 --crop-y 0 http://www.google.com google.jpg