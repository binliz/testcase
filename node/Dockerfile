FROM node:lts-alpine

ENV NODE_ENV=production

WORKDIR /app

#COPY src/package.json .

# set the working direction

# install app dependencies
COPY ./src/package.json /app/package.json
COPY ./src/package-lock.json /app/package-lock.json

# Fix permissions for packages
# RUN npm config set unsafe-perm true

RUN npm install --production=true
RUN npm install -g serve
# Bundle app source

RUN chown -R node:node /app/node_modules
RUN npm ci

# start app
CMD ["npm", "run", "dev"]
