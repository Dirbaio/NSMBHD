# nsmbhd

AcmlmBoard XD fork running the nsmbhd.net forum. PHP 8.5 + nginx + php-fpm in a
single container, MySQL alongside it, deployed to Kubernetes via `deploy.yaml`.

- `webroot/` — the application. Legacy PHP, no framework, no autoloader.
- `conf/` — nginx, php-fpm, php.ini, supervisord, and the container entrypoint.
  These are symlinked over the distro copies at image build time.
- `deploy.yaml` — the whole Kubernetes deployment. `./deploy.sh` builds, ships
  the image over SSH and applies it.
- `./d` — local dev (`./d start`, `./d shell`, `./d dbshell`).

## Intentional decisions that look like security bugs

Don't "fix" these; they are deliberate and were reviewed.

### nginx trusts `X-Forwarded-For` from any source

`conf/nginx.conf` sets `set_real_ip_from 0.0.0.0/0`, so `$remote_addr` is taken
from whatever `X-Forwarded-For` says. This is safe *here* because the pod is only
reachable through the gateway, and the gateway overwrites the client's
`X-Forwarded-For` rather than appending to it — the app never sees a
user-supplied value. IP bans, spam checks and rate limits therefore act on the
real client IP.

### `SERVER_NAME` / `HTTP_HOST` come from the client's `Host` header

`conf/nginx.conf` passes `fastcgi_param SERVER_NAME $http_host`, and
`webroot/lib/links.php` builds absolute URLs (including password-reset mails)
from it. Normally that is a host-header-injection vector, but the gateway routes
by hostname and only forwards requests whose `Host` is `nsmbhd.net`, so the value
cannot be attacker-controlled in production. Keeping it dynamic is what lets the
same image serve `localhost:8000` under `./d start`.

## Conventions

- Commit messages: no `Co-Authored-By` trailers.
- The app runs as uid 1000 on a read-only root filesystem. Only `/data` (the
  uploads PVC), `/tmp` and `/var/lib/php/sessions` are writable — anything that
  needs to write elsewhere is a bug.
- Schema migrations are not reachable over HTTP. They run from the deployment's
  init container (`/app/conf/launch.sh upgrade`); see `deploy.yaml`.
