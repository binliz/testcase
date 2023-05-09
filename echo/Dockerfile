FROM node:lts-alpine

WORKDIR /app
RUN npm install -g socket.io
RUN npm install -g ioredis

COPY socket/package.json socket/package-lock.json ./
RUN npm install
COPY socket/ .
EXPOSE 6001

CMD ["node", "server.js"]
