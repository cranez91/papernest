<template>
	<div class="relative isolate overflow-hidden bg-gray-900 py-16">
		<div class="mx-auto max-w-7xl px-6 lg:px-8">
			<div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-4 lg:max-w-none ">
				<div>
					<h2 class="text-4xl font-semibold tracking-tight text-white">
						Preguntale a nuestro chatbot 🤖
					</h2>
					<p class="mt-4 text-lg text-gray-300">
						Nos ponemos a la moda con la IA y te ofrecemos la posibilidad de preguntar a nuestro amigable chatbot sobre la existencia de los artículos que buscas.
						¡Pregunta!
					</p>
				</div>
				<dl class="grid grid-cols-1 text-white">
					<div class="container mx-auto p-2 text-gray-100">
						<div class="max-w-xl mx-auto mt-10">
							<h2 class="text-2xl font-bold mb-4 text-center">
								¿Qué producto estás buscando?
							</h2>
							<div id="main-chat-box"
								 ref="chatboxContainer"
								 class="border p-4 h-64 overflow-y-scroll bg-gray-100"
								 style="color: #000000; font-size: 12px;"
								 v-html="chatbox">
							</div>
							<form id="main-chat-form"
								  class="mt-4"
								  @submit.prevent="onSubmit">
								<input type="text"
									   id="main-chat-input"
									   class="w-full border p-2"
									   placeholder="Nombre del producto"
									   v-model="form.product"/>
								<button type="submit"
										class="mt-2 px-4 py-2 bg-lime-600 text-white">
									Enviar
								</button>
							</form>
						</div>
					</div>
				</dl>
			</div>
		</div>
		<div aria-hidden="true"
			 class="absolute top-0 left-1/2 -z-10 -translate-x-1/2 blur-3xl xl:-top-6">
			<div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
				 class="aspect-[1155/678] w-[72.1875rem] bg-[#a8e6a3] opacity-30">
			</div>
		</div>
	</div>
</template>

<script>
	import axios from 'axios';
	import { ref, watch, nextTick } from "vue";

    export default {
		data () {
			return {
				form: {
					product: '',
				},
				chatbox: ref(""),
				chatboxContainer: ref(null),
			};
		},
        name: 'HomeChatBot',
        components: {
        },
		methods: {
			async onSubmit() {
				const msg = this.form.product;
				if (!msg) return;

				this.chatbox += `<div><strong>Tú:</strong> ${msg}</div>`;
				this.form.product = '';

				try {
					const response = await axios.post('/api/chat/find', {search: msg});
					const data = response.data;
					if (data && !data.products.length) {
						this.notFoundMessage();
						return;
					}

					this.appendResults(data.products, msg);
				} catch (error) {
					console.error('Ocurrió un error en la petición:', error);
				}
			},

			notFoundMessage() {
				const botMsg = 'Lo siento, por el momento no contamos con ese artículo'; 
				this.chatbox += `<div><strong>Bot:</strong> ${botMsg}</div>`;
			},

			appendResults(products, msg) {
				let message = 'Encontré los siguientes productos: <br/> <hr/>';
				const hasMore = products.length > 3;
				const maxIterations = hasMore ? 3 : products.length;

				for (let el=0 ; el < maxIterations; el++) {
					const product = products[el];
					const link = `/articulo/${product.sku}`;

					let productsDetail = `📝 <strong>Nombre</strong>: ${product.name}<br/>`
                    + `🏷️ <strong>Marca</strong>: ${product.brand}<br/>`
                    + `💲 <strong>Precio</strong>: ${product.price}<br/>`
                    + `📦 <strong>En almacén</strong>: ${product.stock}<br/>`
                    + `Para ver detalle: <a href='${link}' target='_blank'> <strong>> Click aquí <</strong> </a> <br/> <hr/>`;

					message += productsDetail;
				}

				if (hasMore) {
					const searchLink = `/articulos?search=${msg}`;
					message += `Si quieres ver el resto de articulos: <a href='${searchLink}' target='_blank'> <strong>> Ver Más <</strong> </a>`;
				}

				this.chatbox += `<div><strong>Bot:</strong> ${message}</div>`;

				nextTick(() => {
					this.$refs.chatboxContainer.value.scrollTop = this.$refs.chatboxContainer.value.scrollHeight;
				});
			}
  		}
    };

</script>
