
    document.addEventListener('alpine:init', async () => {
        // di php buat api untuk data produk
        Alpine.data('products', () => ({
            items: [],
            // items:[
            //     {id: 1, name: 'Coffe Beans', img: '1.jpg', price: 100},
            //     {id: 2, name: 'Trubus', img: '2.jpg', price: 200},
            // ],
            async fetchProducts() {
                try {
                    const response = await fetch('/skripsivm/php/api-product.php');
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    const data = await response.json();
                    this.items = data.map(product => ({
                        id: product.id,
                        name: product.name,
                        img: product.img,  // Assuming your API returns an 'img' field
                        price: parseInt(product.price),
                        stock: parseInt(product.stock),
                    }));
                } catch (error) {
                    console.error('Fetch error:', error);
                }
            }
        }))

        Alpine.store('cart', {
            items: [],
            total: 0,
            quantity: 0,
            add(newItem){
                const batasan_qty = 1;
                // cek apakah ada barang yang sama di cart
                const cartItem = this.items.find(item => item.id === newItem.id)

                // jika belum ada/masih kosong
                if(!cartItem){
                    if(newItem.stock < 1) return;
                    this.items.push({...newItem, quantity: 1, total: newItem.price})
                    this.quantity++;
                    this.total += newItem.price;
                }else{
                    // jika sudah ada, cek apakah barang beda atau sama dengan yang ada di cart
                    this.items = this.items.map(item => {
                        if(item.id === newItem.id){
                            if(item.stock <= item.quantity || batasan_qty <= item.quantity) return item;
                            item.quantity++
                            item.total = item.price * item.quantity
                            this.quantity++;
                            this.total += item.price;
                            return item
                        }
                        return item
                    })     
                }
                }, 
                remove(id){
                    // cari item yang akan dihapus
                    const cartItem = this.items.find(item => item.id === id)

                    // jika quantity > 1
                        if(cartItem.quantity > 1){
                            // telusuri 1 1 item yang ada di cart
                            this.items = this.items.map(item => {
                                if(item.id === id){
                                    item.quantity--
                                    item.total = item.price * item.quantity
                                    this.quantity--;
                                    this.total -= item.price;
                                    return item
                                }
                                return item
                            })
                        } else {
                            // jika quantity = 1
                            this.items = this.items.filter(item => item.id !== id)
                            this.quantity--;
                            this.total -= cartItem.price;
                        }
                    },
        })

    })
