<script setup>
import { ref } from "vue";

// ===========================
//     IMPORTATION TEST 1
// ===========================
import DataTable from "primevue/datatable";
import Column from "primevue/column";

// ===========================
//     IMPORTATION TEST 2
// ===========================
import Listbox from "primevue/listbox";

// ===========================
//     IMPORTATION TEST 3
// ===========================
import InputNumber from "primevue/inputnumber";
import ProgressBar from "primevue/progressbar";

// ===========================
//      LOGIQUE TEST 1
// ===========================
const products = ref([
	{
		code: "1000",
		name: "Product 1",
		category: "Category 1",
		quantity: 10,
	},
	{
		code: "1001",
		name: "Product 2",
		category: "Category 1",
		quantity: 20,
	},
]);

// ===========================
//      LOGIQUE TEST 2
// ===========================
const selectedCity = ref({ name: "London", code: "LDN" });
const cities = ref([
	{ name: "Rome", code: "RM" },
	{ name: "London", code: "LDN" },
	{ name: "Istanbul", code: "IST" },
	{ name: "Paris", code: "PRS" },
]);

// ===========================
//      LOGIQUE TEST 3
// ===========================
const NumberProgressBar = ref("20");
</script>

<template>
	<article>
		<!-- ===========================
            HTML TEST 1
    ============================ -->
		<div class="card">
			<DataTable :value="products" tableStyle="min-width: 50rem">
				<Column field="code" header="Code"></Column>
				<Column field="name" header="Name"></Column>
				<Column field="category" header="Category"></Column>
				<Column field="quantity" header="Quantity"></Column>
			</DataTable>
		</div>

		<!-- ===========================
            HTML TEST 2
    ============================ -->
		<div>
			<Listbox v-model="selectedCity" :options="cities" optionLabel="name" class="w-full md:w-14rem" />
		</div>

		<!-- ===========================
            HTML TEST 3
    ============================ -->
		<div class="p-input-icon-left">
			<InputNumber v-model="NumberProgressBar" inputId="integeronly" />
			<ProgressBar :value="NumberProgressBar"></ProgressBar>
		</div>
	</article>
</template>

<style lang="scss" scoped>
article {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100%;

	& > div {
		margin-top: 5rem;
	}

	.p-input-icon-left {
		.p-progressbar {
			margin-top: 1rem;
		}
	}
}
</style>
