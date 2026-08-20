// ================================
// Smooth Scroll
// ================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {

    anchor.addEventListener("click", function(e){

        e.preventDefault();

        document.querySelector(this.getAttribute("href"))
        .scrollIntoView({
            behavior:"smooth"
        });

    });

});


// ================================
// Navbar Shadow
// ================================

window.addEventListener("scroll",function(){

    const navbar=document.querySelector(".navbar");

    if(window.scrollY>50){

        navbar.style.boxShadow="0 8px 20px rgba(0,0,0,0.25)";

    }

    else{

        navbar.style.boxShadow="none";

    }

});


// ================================
// Fade Animation
// ================================

const observer=new IntersectionObserver((entries)=>{

entries.forEach(entry=>{

if(entry.isIntersecting){

entry.target.classList.add("show");

}

});

});

document.querySelectorAll(".card,.about,.steps").forEach(el=>{

el.classList.add("hidden");

observer.observe(el);

});



// ================================
// Button Click Effect
// ================================

document.querySelectorAll(".btn1,.btn2").forEach(button=>{

button.addEventListener("click",()=>{

button.style.transform="scale(.95)";

setTimeout(()=>{

button.style.transform="scale(1)";

},150);

});

});


// ================================
// Back To Top Button
// ================================

const topButton=document.createElement("button");

topButton.innerHTML="↑";

topButton.id="topBtn";

document.body.appendChild(topButton);

window.addEventListener("scroll",()=>{

if(window.scrollY>400){

topButton.style.display="block";

}

else{

topButton.style.display="none";

}

});

topButton.onclick=()=>{

window.scrollTo({

top:0,

behavior:"smooth"

});

};


// ================================
// Footer Year
// ================================

const footer=document.querySelector("footer p");

if(footer){

footer.innerHTML=`© ${new Date().getFullYear()} Streetlight Fault Reporting System`;

}
// ================================
// Register Form Validation
// ================================

const registerForm = document.querySelector("form");

if (registerForm) {

    registerForm.addEventListener("submit", function (e) {

        const password = document.querySelector("input[name='password']").value;
        const confirm = document.querySelector("input[name='confirm']").value;

        if (password !== confirm) {

            alert("Passwords do not match.");

            e.preventDefault();
        }

    });

}
