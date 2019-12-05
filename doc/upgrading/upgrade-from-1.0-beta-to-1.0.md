## Upgrading

#### 1) Change configuration

`nihilus_cqrs.query.middlewares` to `nihilus_cqrs.query.middleware_chains`
`nihilus_cqrs.command.middlewares` to `nihilus_cqrs.command.middleware_chains`

#### 2) Change declaration of handledClass in handler

remove for each handler tag `nihilus.cqrs.[query|command]_handler`, this tag is now auto configured.

in each class handler, add method `getHandledClass(): string` and return the FQCN of message handled by your handler.