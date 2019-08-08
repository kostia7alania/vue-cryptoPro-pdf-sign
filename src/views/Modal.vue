<template>
  <transition name="modal">
    <div class="my-modal-mask">
      <div class="my-modal-wrapper">
        <div class="my-modal-container">

          <div class="my-modal-header">
            <slot name="header"> default header </slot>
          </div>

          <div class="my-modal-body">
            <slot name="body"> default body </slot>

          </div>

          <div class="modal-footer">
            <slot name="footer">
             ИЦГПК © {{new Date().getFullYear()}}
             <!-- <button class="modal-default-button" @click="$emit('close')"> OK </button>-->
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'modal-app'
}
</script>

<style lang="scss">

</style>
