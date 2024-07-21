// Toggle class active untuk hamburger menu
const navbarNav = document.querySelector('.navbar-nav');
// ketika hamburger menu di klik
document.querySelector('#hamburger-menu').onclick = () => {
  navbarNav.classList.toggle('active');
};

// Toggle class active untuk search form
const searchForm = document.querySelector('.search-form');
const searchBox = document.querySelector('#search-box');

document.querySelector('#search-button').onclick = (e) => {
  searchForm.classList.toggle('active');
  searchBox.focus();
  e.preventDefault();
};

// Toggle class active untuk shopping cart
const shoppingCart = document.querySelector('.shopping-cart');
document.querySelector('#shopping-cart-button').onclick = (e) => {
  shoppingCart.classList.toggle('active');
  e.preventDefault();
};

// Klik di luar elemen
const hm = document.querySelector('#hamburger-menu');
const sb = document.querySelector('#search-button');
const sc = document.querySelector('#shopping-cart-button');

document.addEventListener('click', function (e) {
  if (!hm.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove('active');
  }

  if (!sb.contains(e.target) && searchForm && !searchForm.contains(e.target)) {
    searchForm.classList.remove('active');
  }

  if (!sc.contains(e.target) && !shoppingCart.contains(e.target)) {
    shoppingCart.classList.remove('active');
  }
});

// Modal Box
const itemDetailModal = document.querySelector('#item-detail-modal');
const itemDetailButtons = document.querySelectorAll('.item-detail-button');

itemDetailButtons.forEach((btn) => {
  btn.onclick = (e) => {
    itemDetailModal.style.display = 'flex';
    e.preventDefault();
  };
});

// klik tombol close modal
if (document.querySelector('.modal .close-icon')) {
  document.querySelector('.modal .close-icon').onclick = (e) => {
    itemDetailModal.style.display = 'none';
    e.preventDefault();
  };
}

// klik di luar modal
window.onclick = (e) => {
  if (e.target === itemDetailModal) {
    itemDetailModal.style.display = 'none';
  }
};


    // form validation
    const checkoutButton = document.querySelector('.checkout-button')
    if (checkoutButton) {
        checkoutButton.disabled = true
    }

    const form = document.querySelector('#checkoutForm')

    form.addEventListener('keyup', function() {
        for (let i = 0; i < form.elements.length; i++) {
            if(form.elements[i].value.length !== 0){
                checkoutButton.classList.remove('disabled')
                checkoutButton.classList.add('disabled')
            }
            else{
                return false
            }
        }
        checkoutButton.disabled = false
        checkoutButton.classList.remove('disabled')
    })

    //kirim data ketika tombol checkout diklik
    checkoutButton.addEventListener('click', async function(e) {
        e.preventDefault()
        const formData = new FormData(form)
        const data = new URLSearchParams(formData)

        // Measure the execution time of the try block
        const startTime = performance.now();

        //minta transaksi token menggunakan ajax / fetch
        try{
            const response = await fetch('php/placeOrder.php',{
                method: 'POST',
                body:data,
            })
            const token = await response.text()
            
            //hanya test saja
            const endTime = performance.now();
            console.log(`Execution time: ${endTime - startTime} milliseconds`);

            window.snap.pay(token, {
                onSuccess: function (result) {
                    // fetch('php/cekStatus.php?' + new URLSearchParams({
                    //     tr_id: result.order_id
                    // }))
                    alert("payment success! -> "+ result.order_id); console.log(result);
                },
                onPending: function (result) {
                  /* You may add your own implementation here */
                  alert("wating your payment! -> "+ result.order_id); console.log(result);
                },
                onError: function (result) {
                  /* You may add your own implementation here */
                  alert("payment failed!"); console.log(result);
                },
                onClose: function () {
                  /* You may add your own implementation here */
                  alert('you closed the popup without finishing the payment');
                }
            });
        } catch (err){
            console.log(err.message)
        }

        
        // window.snap.embed('YOUR_SNAP_TOKEN', {
        //     embedId: 'snap-container'
        //   });
    })

    //format pesan


    // konversi ke Rupiah
const rupiah = (price) => {
    return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', maximumFractionDigits:0}).format(price)
}