window.validacao_email = function() {
    const email = document.querySelector('input[name="email"]').value;

    //Expressão regular para validar o email
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

     //Verifica se o email não está vazio
    if (email === '') {
        alert('Por favor, introduza um email para prosseguir.');
        return false; // Impede o submit
    }
        

    if (!emailValido.test(email)) {
        alert("Por favor, introduz um email válido.");
        return false;
    }

    return true; 
};