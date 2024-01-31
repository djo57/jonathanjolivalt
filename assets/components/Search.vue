<script setup>
import { ref, isProxy, toRaw } from "vue";

const props = defineProps(["skills"]);
let input = ref("");

function filteredList() {
  let rawData = props.skills;
  if (isProxy(props.skills)) {
    rawData = toRaw(props.skills);
  }

  if (rawData && input.value) {
    const filtered = rawData.filter((skill) =>
      skill.name.toLowerCase().includes(input.value.toLowerCase())
    )

    /*if(filtered.length === 1 && input.value != filtered[0].name){
        input.value = filtered[0].name
    }*/

    return filtered;
  }

  return [];
}
</script>

<template>
  <div class="search">
    <input type="text" v-model="input" placeholder="Chercher une compétence..." />
    <TransitionGroup name="fade" tag="p" class="container">
      <div class="item skill" v-for="skill in filteredList()" :key="skill">
        <p>{{ skill.name }}</p>
      </div>
    </TransitionGroup>
    <div class="item error" v-if="input && !filteredList().length">
      <p>Aucune compétence de ce nom :( snif</p>
    </div>
  </div>
</template>

<style scoped>
.search{
    position: absolute;
    z-index: 10;
    right: 0;
    top: 140px;
}
@media only screen and (orientation: portrait){
    .search{
        top: 90px;
        right: 10px;
    }
}
input {
  display: block;
  width: 350px;
  margin: 10px;
  padding: 10px 20px;
  /*background: white url("assets/search-icon.svg") no-repeat 15px center;*/
  background-size: 15px 15px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
    rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
}

.item {
  width: 350px;
  margin: 0 auto 5px auto;
  padding: 10px 20px;
  color: white;
  border-radius: 5px;
  box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px,
    rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
    background-color: #09F;
}

/*.skill {
  background-color: rgb(97, 62, 252);
  cursor: pointer;
}*/

.error {
  background-color: darkred;
  color: white!important;
}
.container {
  position: relative;
  padding: 0;
}

.item {
  font-size: 14px;
  color: black;
  /*width: 100%;
  height: 30px;
  background-color: #f3f3f3;
  border: 1px solid #666;
  box-sizing: border-box;*/
}
/* 1. declare transition */
.fade-move,
.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

/* 2. declare enter from and leave to state */
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scaleY(0.01) translate(30px, 0);
}

/* 3. ensure leaving items are taken out of layout flow so that moving
      animations can be calculated correctly. */
.fade-leave-active {
  position: absolute;
}
</style>
