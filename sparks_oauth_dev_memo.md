Questo file serve come memoria storica per lo sviluppo dell'integrazione OAuth2 universale in SPARKS.

## Architettura Modulare
Il sistema si basa su una **Factory Pattern** per istanziare i diversi provider OAuth2.

### Componenti Principali:
1.  **SmtpOAuthProviderInterface**: Interfaccia che definisce i metodi obbligatori per ogni provider (AuthorizationUrl, AccessToken, RefreshToken).
2.  **BaseOAuthProvider**: Classe astratta che implementa la logica comune (es. rinfresco del token tramite Refresh Token).
3.  **MicrosoftProvider**: Estensione specifica per Azure/Outlook.
4.  **GoogleProvider**: Estensione specifica per Gmail/Workspace (Google Cloud Console).
5.  **DummyProvider**: Il template da copiare per nuovi provider.

## Database (Tabella `smtp_servers`)
Le colonne aggiunte sono:
- `auth_type`: Determina quale classe del provider usare.
- `client_id`, `client_secret`: Le credenziali dell'identità dell'applicazione.
- `access_token`, `refresh_token`: I segreti per l'invio.
- `expires_at`: Timestamp per triggerare il refresh automatico.
- `oauth_scopes`: Stringa/JSON degli scope richiesti dal provider.

## Passi per Aggiungere un Nuovo Provider (es. Yahoo):
1.  Copia `App\Services\OAuth\Providers\DummyProvider.php` in `YahooProvider.php`.
2.  Aggiorna gli endpoint di Autorizzazione e Token.
3.  Implementa la logica specifica se il pacchetto `league/oauth2-client` ha un pacchetto dedicato (es. `hayageek/oauth2-yahoo`).
4.  Aggiunta del tipo al dropdown nel form admin.

## Note di Sicurezza:
- I token nel database dovrebbero essere idealmente crittografati se Sparks gestisce dati ultra-sensibili, ma per ora usiamo la stessa chiave di cifratura delle password SMTP standard.
- Il log HTTP deve oscurare il campo `Authorization: Bearer [TOKEN]` per evitare di scrivere i token nei file di log del webserver.

---
*Memo creato il 2026-02-13 per la migrazione Amatucci.*
