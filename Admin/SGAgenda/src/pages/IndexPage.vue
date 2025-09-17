<template>
  <q-page class="flex flex-col items-center justify-center bg-gray-100">
    <!-- Seção Hero -->
    <section class="w-full bg-gray-200 border-b border-gray-300 py-16 px-6">
      <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl font-bold text-gray-800">
          Simplifique seus negócios com o <span class="text-blue-600">GK Agenda</span>
        </h1>
        <p class="text-lg text-gray-700 mt-4">
          Mais praticidade para você e seus clientes, em qualquer lugar e a qualquer momento.
        </p>
        <div class="mt-8">
          <q-btn
            color="primary"
            label="Começar agora"
            class="px-6 py-3 text-lg rounded-xl shadow-md"
            :to="'/auth/register'"
          />
        </div>
      </div>
    </section>

    <!-- Seção Benefícios -->
    <section class="max-w-5xl mx-auto py-16 px-6 grid md:grid-cols-3 gap-8">
      <div class="bg-white rounded-2xl shadow-md p-6 text-center">
        <h3 class="text-xl font-semibold mb-2">Agendamento Fácil</h3>
        <p class="text-gray-600">
          Seus clientes marcam horários de forma simples e rápida, direto pelo celular ou computador.
        </p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-6 text-center">
        <h3 class="text-xl font-semibold mb-2">Mais Organização</h3>
        <p class="text-gray-600">
          Centralize seus compromissos em um só lugar e nunca mais perca uma reunião importante.
        </p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-6 text-center">
        <h3 class="text-xl font-semibold mb-2">Crescimento do Negócio</h3>
        <p class="text-gray-600">
          Aumente a satisfação dos seus clientes e foque no que realmente importa: oferecer um serviço excelente.
        </p>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="w-full bg-blue-600 text-white py-16 px-6">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold">Pronto para simplificar sua rotina?</h2>
        <p class="mt-4 text-lg">
          Experimente o GK Agenda hoje mesmo e veja como sua vida pode ficar mais prática.
        </p>
        <div class="mt-8">
          <q-btn
            color="white"
            text-color="blue-600"
            label="Criar Conta Grátis"
            class="px-6 py-3 text-lg rounded-xl shadow-md"
            :to="'/auth/register'"
          />
        </div>
      </div>
    </section>
  </q-page>
</template>

<script setup lang="ts">
</script>
