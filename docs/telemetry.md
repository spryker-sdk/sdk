Telemetry
========================

## Config
All the configuration related to telemetry is place in `config/packages/telemetry.yaml`
```yaml
parameters:
  telemetry_enabled: "%env(default:telemetry_enabled_default:bool:TELEMETRY_ENABLED)%"
  telemetry_enabled_default: true

  telemetry_transport: "%env(default:telemetry_transport_default:string:TELEMETRY_TRANSPORT)%"
  telemetry_transport_default: 'file'

  telemetry_server_url: "%env(default:telemetry_server_url_default:string:TELEMETRY_SERVER_URL)%"
  telemetry_server_url_default: ''

  telemetry_synchronizer_batch_size: 200
  telemetry_synchronizer_max_sync_attempts: 3
  telemetry_synchronizer_max_event_ttl_days: 90
  telemetry_synchronizer_lock_ttl_sec: 300

  telemetry_sender_data_lake_timeout_sec: 10
  telemetry_sender_data_lake_connection_timeout_sec: 4

  telemetry_sender_file_report_file_name: 'telemetry_events.json'
  telemetry_sender_file_report_format: 'json'
```

## Events
To implement the custom events or extend the current ones you must implement those interfaces:
`SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface` - generic event data.
`SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface` - event specific data.
`SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface` - event metadata.

## Event transport
To collect and send events to remote storage `SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventsSynchronizerInterface` is used.
It uses `SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface` to send events into the remote server.
To implement the custom sender you must implement this interface and don't forget throw the `SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException` in case the destination server is unreachable.

## Task console event collecting
At the current moment only the tasks commands are collected. It's implemented by listening generic console events.
It uses `\SprykerSdk\Sdk\Infrastructure\Event\Telemetry\TelemetryConsoleEventValidatorInterface` to filter the appropriate events.
All the event listeners are implemented in `SprykerSdk\Sdk\Infrastructure\Event\Telemetry\TelemetryConsoleEventListener`

## Metadata
Project settings `developer_email` and `developer_github_account` are used for user identification.
The project composer.json is used to populate the project name. All this data are sent in event metadata.

## How to disable telemetry
By default, it's enabled. To disable telemetry you can set `TELEMETRY_ENABLED=false` in env variable or update .env file

