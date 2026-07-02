<x-mail::message>
# Félicitations pour votre investissement ! 🌾

Bonjour,

Nous vous confirmons la réception de votre investissement de **{{ number_format($amount) }} FCFA** pour le projet **{{ $projectTitle }}**.
Votre transaction a bien été ancrée sur le réseau Lightning Network de Bitcoin.

<x-mail::panel>
**Preuve Cryptographique (Hash)** : 
{{ $hash }}
</x-mail::panel>

Le contrat numérique liant la coopérative à cet investissement est disponible depuis votre tableau de bord. Vous pouvez également consulter et télécharger votre facture à tout moment.

<x-mail::button :url="url('/invoices')">
Voir mes factures
</x-mail::button>

Merci de soutenir l'agriculture locale avec AgroTrace-BTC ! 🌱

L'équipe AgroTrace-BTC
</x-mail::message>
