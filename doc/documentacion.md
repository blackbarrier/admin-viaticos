# Sistema Viaticos

## Servicio para crear históricas
- Deberá recibir una dependencia o reparticion y la deberá convertir a histórica, quitando o actualizando la delegacion existente y guardando la anterior como una nueva entidad de la misma en su version histórica

## Activades del cron

- Consultar novedades por reprticion y/o dependencias de la reparticion origen y actualizar lo necesario (hacer historico, etc).
- Los viaticos comenzados y no autorizados durante X meses se caducan automaticamente.
- Los viaticos que son anticipo y fueron rendidos pero que no fueron operados en liquidaciones se pasan a rendicion final.
