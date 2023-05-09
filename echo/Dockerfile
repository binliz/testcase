FROM node:lts-alpine

ENV NODE_ENV=production

WORKDIR /app
RUN npm install -g socket.io
RUN npm install -g ioredis

COPY ./socket ./
RUN npm install --production
RUN npm ci

# start app
CMD ["node", "server.js"]
