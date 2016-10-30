FROM ubuntu:16.04

RUN apt-get update && apt-get install -y nano nodejs

WORKDIR /app

# Install app
COPY . /app

RUN groupadd -r app -g 1000 && \
    useradd -u 1000 -r -g app -d /app -s /bin/bash -c "Docker image user" app && \
    chown -R app:app /app

USER app
EXPOSE 1337 1338
CMD ["/usr/bin/nodejs", "/app/main.js"]
