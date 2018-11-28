<template>
    <div>  
            <p v-show="stamp_img.length<1  && doc_prev.length<1 " style="text-align:center"><i class="fa fa-spinner fa-spin" style="font-size:33px"></i></p>
            <div v-show="stamp_img.length>0 && doc_prev.length>0">
                <h3>Положение видимой печати:</h3>  
                <img width="595" height="842" :src="doc_prev" id="watermarked" /> 
                <p>Подтвердить выставленное положение видимой печати</p>
                <button @click="podpis_prev(0)">Подтвердить и подписать</button> 
            </div> 
     </div>
</template>

<script>
export default {
    props: ["doc_prev", "stamp_img", "stampPrevShow"],
    data(){ return { } },
    methods: {
        podpis_prev(stamp_prev){//1-prev;0-final
            this.$emit('podpisat-confirm',0);
        }
    }
}
</script>

<style></style>