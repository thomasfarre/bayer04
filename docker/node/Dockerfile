
FROM node:16.15-alpine
WORKDIR /app
ARG UID=1000
ARG GID=1000
RUN apk add --no-cache ca-certificates wget git shadow
RUN usermod -u ${UID} node && \
  groupmod -g ${GID} node
COPY start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
USER node
CMD ["start"]
