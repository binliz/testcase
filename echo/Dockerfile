FROM node:lts-alpine

ENV NODE_ENV=develop

WORKDIR /app
RUN npm install -g socket.io
RUN npm install -g ioredis
COPY ./socket ./
# start app
CMD ["node", "server.js"]
