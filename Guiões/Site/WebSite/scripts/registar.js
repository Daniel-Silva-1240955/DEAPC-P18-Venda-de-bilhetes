document.addEventListener('DOMContentLoaded', function () 
{
    const inputs = document.querySelectorAll('input.cvv, input.num_cc');

    inputs.forEach(function(input) 
    {
        input.addEventListener('keypress', function(e) 
        {
            if (!/[0-9]/.test(e.key)) 
            {
                e.preventDefault();
            }
        });
    });
});

/*
function validacaoRegisto() 
    {
        const email = document.querySelector('input[name="email"]').value;
        const password = document.getElementById('password').value;
        const num_cc = document.querySelector('input[name="num_cc"]').value;
        const cvv = document.querySelector('input[name="cvv_cc"]').value;
        

        //Expressão regular para validar o email
        const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailValido.test(email)) 
        {
            alert("Por favor, introduz um email válido.");
            return false;
        }

        //Expressão regular para validar a password
        const passwordValida = /^[0-9a-zA-Z$*&@#]{8,}$/;
        if (!passwordValida.test(password)) 
        {
            alert("A palavra-passe deve ter pelo menos 8 caracteres.");
            return false;
        }    

        //Expressão regular para validar o numero do cartao
        const cartaoValido = /^\d{16}$/;
        if (!cartaoValido.test(num_cc)) 
        {
            alert("O número do cartão deve ter 16 dígitos.");
            return false;
        }

        //Expressão regular para validar o cvv
        const cvvValido = /^\d{3}$/;
        if (!cvvValido.test(cvv)) {
            alert("O CVV deve ter 3 dígitos.");
            return false;
        }
        

        return true;
    }
*/


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