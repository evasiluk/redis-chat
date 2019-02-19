<template>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div id="box" class="border" style="height: 350px; overflow: auto;">
                    <div v-for="message in messages" :class="message.user_id == user.id ? 'message send' : 'message received'">
                        <p>{{message.body}}</p>
                    </div>
                </div>
                <span v-if="PeerIsTyping" class="text-secondary">{{PeerIsTyping.name}} is typing...</span>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @keydown="tapListener">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                messages: [],
                textMessage: "",
                PeerIsTyping: false,
                typingTimer: false,
            }
        },
        props: ['room', 'user'],
        mounted() {
            this.getMessages();
            window.Echo.private('room.' + this.room)
                .listen('RoomMessage', ({message}) =>{
                    this.messages.push(message);
                    this.PeerIsTyping = false;
                })
                .listenForWhisper('typing', e => {
                    this.PeerIsTyping = e;

                    if(this.typingTimer) clearTimeout(this.typingTimer);

                    this.typingTimer = setTimeout(()=> {
                        this.PeerIsTyping = false;
                    }, 3000)
                });
        },
        updated: function () {
            this.scrollFeed();
        },
        methods: {
            tapListener(){
                window.Echo.private('room.' + this.room)
                .whisper('typing', {
                    name: this.user.name
                })
            },
            sendMessage() {
                axios.post("/private", {body: this.textMessage, room: this.room})
                .then((response) => {
                    if(response.status == 200) {
                        this.messages.push({body: this.textMessage, user_id: this.user.id});
                        this.textMessage = "";

                    }
                },
                (error) => {
                    alert(error.response.data.message);
                });

            },
            getMessages(){
                axios.post("/room/" + this.room + "/messages")
                .then((response) => {
                    this.messages = response.data;
                    console.log(response.data);

                });
            },
            scrollFeed() {
                $("#box").scrollTop(this.messages.length * 50);
            },
        }
    }
</script>
