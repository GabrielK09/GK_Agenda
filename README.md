-- Comandas a serem rodados --

Iniciar o BackEnd do Admin e alterar o .env de ambos os fronts

- php artisan serve
- php artisan migrate
- php artisan config:ca
- php artisan config:cle
- php artisan route:ca

Fronts:
- npm run dev / quasar dev

.env Admin/SGAgenda

API_URL=http://localhost:8000/api/v1 
API_CNPJ=https://open.cnpja.com/office
API_CEP=viacep.com.br/ws

- A página de agendamento do cliente é a pasta Site/SGAgendaServices, essa sendo separada e que vai apenas fazer as consultas 