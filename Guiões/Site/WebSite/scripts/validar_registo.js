document.addEventListener('DOMContentLoaded', function () 
{
    const inputs = document.querySelectorAll('input.cvv, input.num_cc');

    inputs.forEach(function(input) 
    {
        //Impede a escrita de caracteres inválidos
        input.addEventListener('keypress', function(e) 
        {
            if (!/[0-9]/.test(e.key)) 
            {
                e.preventDefault();
            }
        });

    // Impede colar caracteres inválidos
        input.addEventListener('paste', function(e) 
        {
            const pastedData = e.clipboardData.getData('Text');
            if (!/^\d+$/.test(pastedData)) 
            {
                e.preventDefault();
            }
        });

    });
});

window.validacao = function() {
    const email = document.querySelector('input[name="email"]').value;
    const password = document.getElementById('password').value;
    const num_cc = document.querySelector('input[name="num_cc"]').value;
    const cvv_cc = document.querySelector('input[name="cvv_cc"]').value;
    const validade_cc = document.querySelector('input[name="validade_cc"]').value;

    //Expressão regular para validar o email
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
    //Expressão regular para validar a password
    const passwordValido = /^[0-9a-zA-Z$*&@#]{8,}$/;

    //Expressão regular para validar o numero do cartao
    const cartaoValido = /^\d{16}$/;

    //Expressão regular para validar o cvv
    const cvvValido = /^\d{3}$/;

    if (!emailValido.test(email)) {
        alert("Por favor, introduz um email válido.");
        return false;
    }

    if (!passwordValido.test(password)) {
        alert("A palavra-passe deve ter pelo menos 8 caracteres");
        return false;
    }

    if (!cartaoValido.test(num_cc)) {
        alert("O número do cartão deve ter 16 dígitos.");
        return false;
    }

    if (!cvvValido.test(cvv_cc)) {
        alert("O CVV deve ter 3 dígitos.");
        return false;
    }

    // Confirmar a validade do cartão
    const dataAtual = new Date();
    const anoAtual = dataAtual.getFullYear();
    const mesAtual = dataAtual.getMonth() + 1; // +1 porque o mês no JS vai de 0 a 11

    const [anoValidade, mesValidade] = validade_cc.split('-').map(Number);

    if (anoValidade < anoAtual || (anoValidade === anoAtual && mesValidade < mesAtual)) {
        alert("A validade do cartão não pode ser anterior à data atual.");
        return false;
    }

    return true; 
};



/*
/^
    (?=.*\d)              // deve conter ao menos um dígito
    
    (?=.*[a-z])           // deve conter ao menos uma letra minúscula
    
    (?=.*[A-Z])           // deve conter ao menos uma letra maiúscula
    
    (?=.*[$*&@#])         // deve conter ao menos um caractere especial
    
    [0-9a-zA-Z$*&@#]{8,}  // deve conter ao menos 8 dos caracteres mencionados

    ^[^\s@]+ → começa com pelo menos um caracter que não é espaço nem @

    @ → tem de conter um @

    [^\s@]+ → depois do @, pelo menos um caracter que não é espaço nem @

    \. → tem de conter um ponto . (o \ é necessário para o ponto ser entendido como Escape Caracter)

    [^\s@]+$ → pelo menos um caracter após o ponto, até ao fim da string
$/
*/