<template>
    <div class="box">
        <div class="title">
            <input type="checkbox">
            <label for="">{{ item.aspect }}</label>
        </div>
        <ul class="d-flex flex-wrap">
            <li v-for="option in item.options">
                <input type="checkbox">
                <label for="">{{ option }}</label>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: ['item']
}
</script>